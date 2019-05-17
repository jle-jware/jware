<template>
	<div class="row">
	    <div class="col-md-3 col-sm-12">
	        <input type="text" class="form-control" placeholder="Search" title="Search Item" v-model="generalFilter" @keyup="search">
	    </div>
		<div class="col-md-9 col-sm-12 text-right header-menu-nav">
            <slot name="navigations"></slot>
            <router-link :to="{ name: createRoute,  params: { hash: hash || $route.params.hash } }" class="btn btn-link">
                <i class="fa fa-plus-square" title="Add Item" style="font-size:20px;  color: black;"></i>
            </router-link>
	        <!-- <a class="btn btn-link" :href="createLink"><i class="far fa-plus-square" style="font-size:20px;  color: black;"></i></a> -->
	        <button class="btn btn-link" title="Edit Item" @click="editForm" :disabled="selection.length != 1"><i class="fa fa-edit" style="font-size:20px; color: black;"></i></button>
	        <button class="btn btn-link" title="Delete Item" :disabled="!selection.length" @click="deleteSelected"><i class="fa fa-trash" style="font-size:20px; color:black;"></i></button>
	        <div class="dropdown export" title="Download List" >
	            <button class="btn btn-link  dropdown-toggle" data-toggle="dropdown" 
	                 aria-haspopup="true" aria-expanded="false" style="font-size: 20px;color: black;" ref="dropdownBtn">
	                 <i class="fa fa-download"></i> 
	             </button>
	            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	                <a class="dropdown-item" href="#" @click.prevent="exportToExcel">Excel</a>
	                <a class="dropdown-item" href="#" @click.prevent="exportToPdf">PDF</a>
	            </div>
	        </div>
	    </div>
    </div>
</template>
<script>
import TableExport from 'tableexport'
import jsPDF from 'jspdf'
require('jspdf-autotable');
import Axios from 'axios';
export default {
	props: ['query', 'selection', 'createRoute', 'deleteUrl', 'editRoute', 'hash', 'tableClass'],
	data: () => ({
		generalFilter: ''
	}),
    mounted () {
        const $el = $(this.$el)
        $(this.$refs.dropdownBtn).on('click', this.toggle)
        $(document).on('click', e => {
          $(e.target).closest($el).length || $el.removeClass('open')
        });
    },
	methods: {
        toggle () {
            $(this.$el).toggleClass('open')
        },
        editForm() { 
            if(this.editRoute != undefined){
                var hash = this.hash || this.$route.params.hash;
                this.$router.push({name: this.editRoute, params: { id: this.selection[0].id , hash: hash,  data: this.selection[0] } });
            }
        },
		search () {   
			const { query } = this
            console.log(query);
			this.$set(query, 'query', this.generalFilter)
            this.$set(query, 'csrf', Math.random(1000000000, 999999999))
			query.offset = 0;
		},
        deleteSelected() {
            var confirmDeletion = confirm("Are you sure you want to delete selected rows?");
            if(!confirmDeletion) return;
            var delete_ids = this.selection.filter(obj => obj.id)
            Axios.delete(this.deleteUrl, { data: delete_ids })
                .then(response => { 
                    this.search()
                })
                .catch( (err) => {
                    console.log(err.response)
                });
        },
        exportToExcel() {
            var instance = new TableExport(document.getElementsByClassName(this.tableClass), {
                headers: true,                              // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
                formats: ['xlsx'],                          // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
                filename: 'id',                             // (id, String), filename for the downloaded file, (default: 'id')
                bootstrap: false,                           // (Boolean), style buttons using bootstrap, (default: true)
                exportButtons: false,                        
                position: 'bottom',                         // (top, bottom), position of the caption element relative to table, (default: 'bottom')
                ignoreRows: null,                           // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
                ignoreCols: null,                           // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
                trimWhitespace: true                        // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) 
            });

            var exportData = instance.getExportData()['tableexport-1']['xlsx'];
            instance.export2file(exportData.data, exportData.mimeType, exportData.filename, exportData.fileExtension);
        },
        exportToPdf() {
            var table = this.tableToJson($('.' + this.tableClass)[0])
            var keys = Object.keys(table[0]);
            var values = table.map(function(item) { return Object.values(item); });
            var doc = new jsPDF('p', 'pt');
            doc.autoTable(keys, values);
            doc.save('table.pdf');
        },
        tableToJson(table) {
            var data = [];
            var headers = [];
            for (var i=0; i<table.rows[0].cells.length; i++) {
                var columnName = table.rows[0].cells[i].innerText || ("unknow" +i);
                columnName = columnName.toLowerCase().replace(/ /gi,'');
                console.log(columnName);
                headers[i] = columnName;
            }
            for (var i=1; i<table.rows.length; i++) {
                var tableRow = table.rows[i];
                var rowData = {};
                for (var j=0; j<tableRow.cells.length; j++) {
                    rowData[ headers[j] ] = tableRow.cells[j].innerText || "-";
                }
                console.log(rowData)
                data.push(rowData);
            }       
            return data;
        },
	}
}
</script>