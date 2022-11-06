<template>
<div class="row m-0 py-3 px-3">
    <form action="#" @submit.prevent="postSizes">
        <div class="col-md-12">
            <h5>Sizes & quantities</h5>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 py-1">
                    <label for="">Size</label>
                    <input placeholder="Size" required v-model="formData.size" type="text" class="form-control">
                </div>
                <div class="col-md-6 py-1">
                    <label for="">Quantity</label>
                    <input placeholder="Quantity" required v-model="formData.quantity" type="number" class="form-control">
                </div>   
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-primary btn-sm form-control">Add</button>
        </div>
    </form>
</div>
</template>
<script>
export default {
    props:['color_id', 'product_id'],
    data(){
        return{
            formData:{
                size:null,
                quantity:null
            }
        }
    },
    mounted(){
       this.formData.color_id = this.color_id
       this.formData.product_id = this.product_id
    },
    methods:{
        async postSizes(){
            await axios.post('/addSizes', this.formData)
            .then( response =>{
                this.$emit('close')
            })
        },
    }

}
</script>