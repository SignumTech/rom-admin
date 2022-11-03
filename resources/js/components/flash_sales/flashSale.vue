<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Flash Sales</strong></h5>
    </div>
    <div class="col-md-12">
        <button @click="addFlash()" class="btn btn-primary btn-sm float-end shadow text-white"><span class="fa fa-plus"></span> Add Flash Sale</button>
    </div>
    <div v-for="flash,index in flashes" :key="index" class="col-md-4 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm border-3 flash-hov">
            <div class="row">
                <div class="col-md-12 border-bottom">
                    <router-link :to="`/admin/flashSaleDetail/`+flash.id">
                        <h5><i class="fa fa-bolt"></i> Flash Sale {{index +1}} <span v-if="flash.current" class="float-end bg-warning p-1 rounded-1 shadow-sm"><strong>Active</strong></span></h5>
                        <h6 class="mt-2">Starts at | {{flash.starts_at | moment("ddd, MMM Do YYYY h:mm:ss")}} </h6>
                        <h6 class="mt-2">Ends at | {{flash.expiry_date | moment("ddd, MMM Do YYYY h:mm:ss")}}</h6>
                    </router-link>
                </div>
                <div class="col-md-12 mt-2">
                    <a role="button"><span class="fa fa-trash-alt float-end"></span></a>
                    <a role="button"><span class="fa fa-edit float-end me-3"></span></a>
                </div>
            </div>
        </div>
    </div>
</div>    
</template>
<script>
import addFlashVue from './addFlash.vue';

export default {
    data(){
        return{
            flashes:{}
        }
    },
    components:{
        addFlashVue
    },
    mounted(){
        this.getFlashes()
        feather.replace();
    },
    methods:{
        async getFlashes(){
            await axios.get('/flashSales')
            .then( response =>{
                this.flashes = response.data
            })
        },
        updateData(){
            this.getFlashes()    
        },
        addFlash(){
            this.$modal.show(
                addFlashVue,
                {},
                {"width":"600px", "height":"auto"},
                {"closed":this.updateData}
            )
        },
    }
 
}
</script>