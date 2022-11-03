<template>
<div class="row mt-4">
    <div class="col-md-4">
        <h5><strong>Flash Sale Details <span v-if="flash.current" class="bg-warning p-1 rounded-1 shadow-sm fs-6"><span class="fa fa-bolt"></span> Active</span></strong></h5>
    </div>
    <div class="col-md-4">
        <h5><strong>Starts at | {{flash.starts_at | moment("ddd, MMM Do YYYY h:mm:ss")}}</strong></h5>
    </div>
    <div class="col-md-4">
        <h5><strong>Ends at | {{flash.expiry_date | moment("ddd, MMM Do YYYY h:mm:ss")}}</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <table class="table px-2 table-sm mt-2">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Regular Price</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Promotion</th>
                        <th>Created Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td class="align-middle">
                            <img :src="`/storage/products/`+JSON.parse(product.p_image).main" class="img img-fluid img-thumb cat_img rounded-1" alt="">
                        </td>
                        <td class="align-middle">{{product.p_name}}</td>
                        <td class="align-middle">{{product.price}} Birr</td>
                        
                        <td class="align-middle">{{product.discount}} %</td>
                        <td class="align-middle">{{product.p_status}}</td>
                        <td class="align-middle">{{product.promotion_status}}</td>
                        <td class="align-middle">{{product.created_at | moment("ddd, MMM Do YYYY")}}</td>
                        <td class="align-middle">
                            <router-link to="#" class="float-end "><span class="fa fa-trash-alt"></span></router-link>
                            <router-link :to="`/admin/editProduct/`+product.id" class="float-end me-3"><span class="fa fa-edit"></span></router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            products:{},
            flash:{}
        }
    },
    mounted(){
        this.getProducts()
        this.getFlashDetails()
    },
    methods:{
        async getFlashDetails(){
            await axios.get('/flashSales/'+this.$route.params.id)
            .then( response =>{
                this.flash = response.data
            })
        },
        async getProducts(){
            await axios.get('/getFlashProducts/'+this.$route.params.id)
            .then( response =>{
                this.products = response.data
            })
        },
    }
}
</script>