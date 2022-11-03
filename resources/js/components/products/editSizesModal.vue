<template>
<div class="row m-0 py-3 px-3">
    <form action="#" @submit.prevent="updateSizes">
        <div class="col-md-12">
            <h5>Sizes & quantities <span @click="addSizes()" class="fa fa-plus float-end"></span></h5>
        </div>
        <div class="col-md-12">
            <div v-for="size,index in sizes[currentColor]" :key="index" class="row">
                <div class="col-md-6 py-1">
                    <label for="">Size</label>
                    <input required v-model="size.size" type="text" class="form-control">
                </div>
                <div class="col-md-6 py-1">
                    <label for="">Quantity</label>
                    <input required v-model="size.quantity" type="number" class="form-control">
                </div>   
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-primary btn-sm form-control">Update</button>
        </div>
    </form>
</div>
</template>
<script>
export default {
    props:['sizeData','currentColor', 'product_id'],
    data(){
        return{
            sizes:[]
        }
    },
    mounted(){
        this.sizes = this.sizeData
    },
    methods:{
        async updateSizes(){
            await axios.post('/updateSizes', {sizes:this.sizes[this.currentColor],color:this.currentColor,product_id:this.product_id})
            .then( response =>{
                this.$emit('close')
            })
        },
        addSizes(){
            
            this.sizes[this.currentColor].push({size:'',quantity:'', id:0})
            console.log(this.sizes[this.currentColor])
        }
    }

}
</script>