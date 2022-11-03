<template>
<div class="col-md-12 p-2">
<color-panel class="d-block m-auto" v-model="color"></color-panel>
<button @click="addColor()" class="btn btn-primary form-control mt-2">Add Color</button>
</div>

</template>
<script>
export default {
    props:['colorData'],
    data(){
        return{
            color:""
        }
    },
    methods:{
        addColor(){
            this.colorData.push(
                {
                    color:this.color,
                    price:"",
                    sizes:[{size:'',quantity:''}],
                    pictures:[]
                }
            );
            this.$emit('close')
        }
    }
}
</script>