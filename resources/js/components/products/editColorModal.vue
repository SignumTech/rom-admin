<template>
<div class="col-md-12 p-2">
    <color-panel class="d-block m-auto" v-model="picked_color"></color-panel>
    <button @click="editColor()" class="btn btn-primary form-control mt-2">update Color</button>
</div>

</template>
<script>
export default {
    props:['color'],
    data(){
        return{
            picked_color:""
        }
    },
    mounted(){
        this.picked_color = this.color.color
    },
    methods:{
        async editColor(){
            await axios.put('/updateColor/'+this.color.id, {color:this.picked_color})
            .then( response =>{
                this.$emit('close')
            })
        }
    }
}
</script>