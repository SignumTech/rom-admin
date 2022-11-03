<template>
<div class="container justify-content-center">
    <div class="row mt-5">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="bg-white rounded-sm p-4">
                <form action="#" @submit.prevent="sendReset">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center"><strong>Please enter your email below</strong></h5>
                            <h6 class="text-center">We will send you an OTP on your email</h6>
                        </div>
                        <div class="col-md-12 mt-3">
                            <input required v-model="formData.email" type="email" class="form-control form-control-auth" placeholder="Email address">
                            <h6 v-if="emailErrors" class="text-danger m-0">The email doesnt exist!</h6>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div v-if="regLoading" class="d-flex justify-content-center">
                                <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                            </div>
                            <button type="submit" v-if="!regLoading" class="btn btn-primary form-control form-control-auth-btn"><h5 class="m-0">Continue</h5></button>
                            
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
    data(){
        return{
            formData:{
                email: null
            },
            regLoading:false,
            emailErrors: false,
        }
    },
    methods:{
        async sendReset(){
            this.regLoading = true
            await axios.post('/foregetPasswordMailer', this.formData)
            .then( response =>{
                this.$router.push({ name: 'ResetOTP', params:{
                    user_id: response.data.id
                }});
            })
            .catch( error =>{
                
                if(error.response.status == 422){
                    this.emailErrors = true
                }
                this.regLoading = false
            })
        },
    }
}
</script>