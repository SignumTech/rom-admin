<template>
    <div class="col-md-12 p-2">
    <color-panel class="d-block m-auto" v-model="color"></color-panel>
    <button @click="addColor()" class="btn btn-primary form-control mt-2">Add Color</button>
    </div>
    
    </template>
    <script>
    export default {
        props:['colors','pictures', 'sizes'],
        data(){
            return{
                color:""
            }
        },
        methods:{
            addColor(){
                
                this.colors.push(this.color)
                this.pictures[this.color] = [];
                this.sizes[this.color] = [];
                this.$emit('close')
            }
        }
    }
    </script>