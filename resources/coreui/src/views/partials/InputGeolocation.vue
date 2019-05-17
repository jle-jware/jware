<template>
    <div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="">Address</label>
                <vue-google-autocomplete
                    ref="address"
                    id="map"
                    classname="form-control"
                    :country="['au', 'nz']"
                    placeholder="Please type your address"
                    v-on:placechanged="getAddressData">
                </vue-google-autocomplete>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input
                    type="text"
                    ref="post code"
                    class="form-control"
                    placeholder="Post code"
                    v-model="postal_code"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        props: ['fieldName', 'value', 'post_code', 'location'],
        components: { VueGoogleAutocomplete },
        created() {
            if(this.location) this.address = this.location;
            if(this.postcode) this.postal_code = this.postcode;
        },
        data: function () {
            return {
              address: '',
              postal_code: ''
            }
        },
        methods: {
            getAddressData: function (addressData, placeResultData, id) {
                this.address = addressData;
                this.postal_code = addressData.postal_code
                this.$parent.$emit('address', addressData)
            }
        }
    }
</script>