<template>
    <div class="form-row">
        <div class="col-md-12 mb-3">
             <label :for="fieldName" v-show="fieldName">{{ columnTitleCase(fieldName) }}</label>
            <input
                :placeholder="columnTitleCase(fieldName) + isRequired(vValidate)" 
                :class="{
                    'form-control': true, 
                    'is-invalid': errors.first(fieldName), 
                    'is-valid': !errors.first(fieldName) && value && value.length > 2 
                }"
                v-validate="vValidate" 
                :type="user_type"
                :name="fieldName"
                :value="value"
                :disabled="disabled"
                @input="$emit('input', $event.target.value)"
                @change="$emit('input', $event.target.value)"
                
            />
            <div class="invalid-feedback" v-if="errors.first(fieldName)">
                {{ errors.first(fieldName).replace(fieldName, "'" + columnTitleCase(fieldName) + "'" )  }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['fieldName', 'value', 'v-validate', 'type', 'disabled'],
    inject: ['$validator'],
    data() {
        return {
            user_type: 'text',
        }
    },
    created() {
        var self = this;
        if(this.type)
            this.user_type = this.type

        this.$parent.$on('error', (payload) => {
            for (var k in payload.error){
                if (payload.error.hasOwnProperty(k)) {
                    this.errors.add(k, payload.error[k][0]);
                }
            }
        });
    },
    methods: {
        columnTitleCase(str) {
            if(typeof str == undefined) {
                alert("Please write all columns name in binding");
                return;
            }

            return str.split('_').map(function (item) {
                return item.charAt(0).toUpperCase() + item.substring(1);
            }).join(' ');
        },
        isRequired(input) {
            return input && input.indexOf('required') > -1 ? ' *' : '';
        }
    }
}
</script>

