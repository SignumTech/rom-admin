<template>
    <div class="col-md-12">
        <h4 class="text-center mt-3"><strong>ORDER DETAILS</strong></h4>
        <div class="bg-white rounded-1 p-3">
            <div v-if="order.order_status == `PROCESSING`" class="row m-0">
                <div class="col-md-12 p-2 mt-3">
                    <button @click="shipOrder()" class="btn btn-primary px-4 rounded-1 float-end"><span class="fa fa-shipping-fast"></span> Ship Order</button>
                </div>
            </div>
            <div v-if="loading" class="row m-0">
                <div class="col-md-12 p-5 mt-3">
                    <div class="d-flex justify-content-center align-self-center">
                        <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                    </div>
                </div> 
            </div>
            <div v-if="!loading" class="row m-0">
                <div class="col-md-6 mt-3">
                    <h6 class="text-center">Order No.</h6>
                    <h5 class="text-center"><strong>{{order.order_no}}</strong></h5>
                </div>
                <div class="col-md-6 mt-3 border-start">
                    <h6 class="text-center">Order Date</h6>
                    <h5 class="text-center"><strong>{{order.created_at | moment("ddd, MMM Do YYYY")}}</strong></h5>
                </div> 
                <div class="col-md-12 mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">Products</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ot in orderItems" :key="ot.id" class="align-middle">
                                <td class="ps-2 text-center"><img :src="`http://rom/storage/productsThumb/`+ot.item_details.p_image" class="img img-fluid" style="width:70px;height:auto"></td>
                                <td class="ps-2">
                                    <h6>{{ot.item_details.p_name}}</h6>
                                    <h6>
                                        <span class="badge rounded-pill px-3"  :style="{backgroundColor:ot.item_details.color}">{{ot.item_details.color}}</span> / 
                                        <span class="badge rounded-pill bg-light shadow-sm text-dark px-3 border"><strong>{{ot.item_details.size}}</strong></span>
                                    </h6>
                                    
                                </td>
                                <td class="ps-2 text-center">{{ot.quantity}}</td>
                                <td class="ps-2 text-center">{{ot.item_details.price | numFormat}} ETB</td>
                                <td class="ps-2 text-center">{{order.order_status}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>    
                <div class="col-md-8 mt-3">
    
                </div>     
                 <div class="col-md-4 border-bottom border-3 mt-3">
                    <h6 class="mt-2">Retail Price: <span class="float-end">{{order.total | numFormat}} ETB</span></h6>
                    <h6 class="mt-2">Sub Total: <span class="float-end">{{order.total | numFormat}} ETB</span></h6>
                    <h5 class="mt-2"><strong>Total: <span class="float-end">{{order.total | numFormat}} ETB</span></strong></h5>
                 </div>
                 <div class="col-md-6 mt-5">
                    <h5 class="border-bottom">Shipping Information</h5>
                    <div class="rounded-1 border-start border-warning border-5 p-3" style="cursor:pointer">
                        <h5><strong>{{address.f_name}} {{address.l_name}}</strong> </h5>
                        <h6>+251-{{address.phone_no}}</h6>
                        <h6>{{address.city}} - {{address.state}}</h6>
                        <h6>{{address.address_1}}</h6>
                    </div>
                 </div>
                 <div class="col-md-6 mt-5">
                    <h5 class="border-bottom">Billing Information</h5>
                    <div class="rounded-1 border-start border-warning border-5 p-3" style="cursor:pointer">
                        <h5><strong>{{address.f_name}} {{address.l_name}}</strong> </h5>
                        <h6>+251-{{address.phone_no}}</h6>
                        <h6>{{address.city}} - {{address.state}}</h6>
                        <h6>{{address.address_1}}</h6>
                    </div>
                 </div>
            </div>
        </div>
    </div>
    </template>
    <script>
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
    export default {
        components:{
            PulseLoader
        },
        data(){
            return{
                order:{},
                orderItems:[],
                address:{},
                loading:true
            }
        },
        mounted(){
            this.getOrder()
        },
        methods:{
            async shipOrder(){
                await axios.post('/shipOrder', {order_id:this.order.id})
                .then( response => {
                    window.location.replace('/admin/orders')
                })
            },
            async getOrder(){
                this.loading = true
                await axios.get('/orders/'+this.$route.params.id)
                .then( response =>{
                    this.order = response.data.order_details
                    this.orderItems = response.data.order_items
                    
                    this.getAddress(this.order.delivery_details)
                })
            },
            async getAddress(id){
                await axios.get('/showAddress/'+id)
                .then( response =>{
                    this.address = response.data
                    this.loading = false
                })
            }
        }
    }
    </script>