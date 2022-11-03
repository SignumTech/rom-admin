<template>
<div class="container justify-content-center">
    <div class="row mt-5">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="bg-white rounded-sm p-2">
                <div class="row">
                    <div class="col-md-12 pt-3">
                        <h4 class="text-center"><strong>SIGN IN</strong></h4>
                        <form action="#" @submit.prevent="submit">
                            <div class="row m-0 mt-4">
                                <div class="col-md-12">
                                    <label for="email">Email Address</label>
                                    <input v-model="logForm.email" required type="email" class="form-control form-control-auth" placeholder="Email address">
                                </div>
                            </div>
                            <div class="row m-0 mt-4">
                                <div class="col-md-12">
                                    <label for="password">Password</label>
                                    <input v-model="logForm.password" required type="password" class="form-control form-control-auth" placeholder="Password">
                                </div>
                            </div>   
                            <div class="row m-0 mt-4">
                                <div class="col-md-12">
                                    <h6 v-if="valErrors" class="text-danger text-center"><strong>You entered wrong credentials!</strong></h6>
                                    <div v-if="logLoading" class="d-flex justify-content-center">
                                        <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                                    </div>
                                    <button type="submit" v-if="!logLoading" class="btn btn-primary form-control form-control-auth-btn"><h5 class="m-0"><strong>SIGN IN</strong></h5></button>
                                    <router-link to="/forgetPassword"><h6 class="text-center mt-3">Forgot your password?</h6></router-link>
                                </div>
                                
                                <div class="col-md-6 mt-3 border-end">
                                    <a href="/auth/google/redirect"><img src="/storage/settings/google.png" class="img img-fluid d-flex m-auto" alt="" style="width:40px; height:40px"></a>
                                    <h6 class="text-center mt-2">Sign in with google</h6>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <a href="/auth/facebook/redirect"><img src="/storage/settings/facebook.png" class="img img-fluid d-flex m-auto" alt="" style="width:40px; height:40px"></a>
                                    <h6 class="text-center mt-2">Sign in with facebook</h6>
                                </div>
                            </div>
                        </form>                      
                    </div>
                </div>
            </div>
        </div>  
        <div class="col-md-4"></div>      
    </div>

</div>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import { mapActions } from 'vuex'
export default {
        components:{
            PulseLoader
        },
        data(){
            return{
                logForm: {
                    email: null,
                    password: null,
                },
                logLoading:false,
                valErrors: false,
                loginErrors:{}
            }
        },
        mounted(){
            
        },
        methods:{
            ...mapActions({
                signIn: 'auth/signIn'
            }),
            async submit () {
                this.loading = true
                await this.signIn(this.logForm)
                window.location.replace('/')
                this.loading = false
            }

        }
    }
</script>