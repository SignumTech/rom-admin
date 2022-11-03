<template>
    <form action="#" @submit.prevent="addFlash">
        <div class="row m-0 p-2 py-3">
            <div class="col-md-12 mb-2">
                <h5 class="text-center">Add to a flash sale</h5>
            </div><hr>
            <div class="col-md-3">
                <img class="img img-fluid" :src="`/storage/products/`+JSON.parse(product.p_image).main" alt="">
            </div>
            <div class="col-md-8">
                <h6>{{product.p_name}}</h6>
                <h6>{{product.price}} ETB</h6>
            </div><hr class="mt-3">
            <div class="col-md-12 mt-3">
                <label for="">Select a flash sale period</label>
                <select required v-model="formData.flashPeriod" class="form-select">
                    <option v-for="flash,index in flashes" :value="flash.id" :key="index">
                        <h6><strong>Flash Sale {{index + 1}} | </strong></h6>
                        <h6>{{flash.starts_at | moment("ddd, MMM Do YYYY h:mm:ss a")}} - {{flash.expiry_date | moment("ddd, MMM Do YYYY h:mm:ss")}}</h6>
                        
                    </option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <label for="">Discount %</label>
                <input v-model="formData.discount" required type="number" min="1" max="99" class="form-control" placeholder="Discount %">
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control">ADD TO FLASH SALE</button>
            </div>
        </div> 
    </form>
</template>
<script>
export default {
    props:["product"],
    data(){
        return{
            flashes:{},
            formData:{
                flashPeriod:"",
                discount:"",
                p_id:this.product.id
            }
        }
    },
    mounted(){
        this.getFlashes()
    },
    methods:{
        async addFlash(){
            await axios.post('/addToflashSales', this.formData)
            .then( response =>{
                this.$emit('close')
            })
        },
        async getFlashes(){
            await axios.get('/flashSales')
            .then( response =>{
                this.flashes = response.data
            })
        }
    }
}
</script>