<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Customers</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 shadow-sm p-2">
            <input type="text" class="form-control mb-2 rounded-1">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Address</th>
                        <th>Spent</th>
                        <th></th>
                    </tr>                
                </thead>
                <tbody>
                    <tr v-for="customer,index in customers" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{customer.f_name}} {{customer.l_name}}</td>
                        <td>{{customer.email}}</td>
                        <td>{{customer.created_at | moment("MMM Do YYYY")}}</td>
                        <td>{{customer.address_1}}</td>
                        <td><strong>{{customer.spent}} ETB</strong></td>
                        <td>
                            <span class="fa fa-trash-alt float-end me-5"></span>
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
            customers:{},
            loading:true
        }
    },
    mounted(){
        this.getCustomers()
    },
    methods:{
        async getCustomers(){
            this.loading = true
            await axios.get('/getCustomers')
            .then( response =>{
                this.customers = response.data
                this.loading = false
            })
        }
    }
}
</script>