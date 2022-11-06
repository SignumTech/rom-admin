<template>
<div class="col-md-12 p-2">
<color-panel class="d-block m-auto" v-model="color"></color-panel>
<button @click="addColor()" class="btn btn-primary form-control mt-2">Add Color</button>
</div>

</template>
<script>
export default {
    props:['product_id'],
    data(){
        return{
            color:""
        }
    },
    methods:{
        async addColor(){
            await axios.post('/addColor', {color:this.color, product_id:this.product_id})
            .then( response =>{
                this.$emit('close')
            })
        }
    }
}
</script>