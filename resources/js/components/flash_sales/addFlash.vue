<template>
    <form action="#" @submit.prevent="addFlash">
        <div class="row m-0 p-2">
            <div class="col-md-12 mb-2">
                <h5 class="text-center">Add Flash Sale</h5>
            </div>
            
                <div class="col-md-6">
                    <label for="">Flash sale starts at</label> 
                    <input required v-model="formData.starts_at" type="datetime-local" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="">Flash sale ends at</label>
                    <input required v-model="formData.ends_at" type="datetime-local" class="form-control">
                </div>
                <div class="col-md-12 mt-2">
                    <h6 v-if="dateErrorExists" class="text-danger">{{dateError}}</h6>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary px-4 rounded-1 d-block m-auto"><span class="fa fa-plus"></span> Add Flash Sale</button>
                </div>
        </div> 
    </form>
</template>
<script>
export default {
    data(){
        return{
            formData:{
                starts_at:"",
                ends_at:""
            },
            dateError:"",
            dateErrorExists:false
        }
    },
    methods:{
        async addFlash(){
            await axios.post('/flashSales', this.formData)
            .then( response =>{
                this.$emit('close')
            })
            .catch( error =>{
                if(error.response.status == 422){
                    this.dateError = error.response.data
                    this.dateErrorExists = true
                }
                
            })
        }
    }
}
</script>