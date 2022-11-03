<template>
<div class="container justify-content-center">
    <div class="row mt-5">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="bg-white rounded-sm p-4">
                <form action="#" @submit.prevent="resetPassword">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center"><strong>Please enter your new password below</strong></h5>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="new_password">New password</label>
                            <input required v-model="formData.password" type="password" class="form-control form-control-auth" placeholder="Password">
                            <h6 v-for="er in passErrors.password" :key="er.id" class="text-danger m-0">{{er}}</h6>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="new_password">Confirm password</label>
                            <input required v-model="formData.password_confirmation" type="password" class="form-control form-control-auth" placeholder="Confirm Password">
                        </div>
                        <div class="col-md-12 mt-3">
                            <div v-if="regLoading" class="d-flex justify-content-center">
                                <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                            </div>
                            <button v-if="!regLoading" type="submit" class="btn btn-primary form-control form-control-auth-btn"><h5 class="m-0">Reste</h5></button>
                        </div>
                    </div>
                </form>
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
    props:['user_id'],
    data(){
        return{
            formData:{
                password: null,
                password_confirmation: null,
                user_id: null,
            },
            regLoading:false,
            passErrors:{},
        }
    },
    mounted(){
        this.formData.user_id = this.user_id
    },
    methods:{
        async resetPassword(){
            this.regLoading = true
            await axios.post('/resetPassword', this.formData)
            .then( response =>{
                window.location.replace('/myAccount');
            })
            .catch( error =>{
                if(error.response.status == 422){
                    this.passErrors = error.response.data.errors
                }
                this.regLoading = false
            })
        },
    }
}
</script>