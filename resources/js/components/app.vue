<template>
<div class="container-fluid p-0 overflow-hidden mob_hide" style="height: 100vh">
  <div class="row m-0">
      <div class="p-0 shadow-sm" style="background-color:#fff; width:18%" v-if="authenticated">
          <div class="row m-0" style="background-color: #bf7f25;">
              <div class="col-md-12 mt-5 mb-5">
                  <div class="rounded-circle shadow-sm m-auto d-flex align-items-center overflow-hidden" style="width: 100px; height: 100px;">
                      <img class="img img-fluid d-block m-auto" src="/storage/settings/company.png" style="width:200px; height: auto">
                  </div>
              </div>
          </div>
          <nav v-if="authenticated" id="sidebar" class="border-end" style="height: 100vh; overflow-y: auto">
            <ul class="list-unstyled components">
              <li v-if="$store.state.auth.permissions.Dashboard" :class="$route.path == `/admin/adminDash` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/adminDash"><i data-feather="home"></i> Dashboard <span class="sr-only">(current)</span></router-link>
              </li>
              <li v-if="$store.state.auth.permissions.Products" :class="$route.path == `/admin/products` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/products"><i data-feather="box"></i> Products</router-link>
              </li>
              <li v-if="$store.state.auth.permissions.Orders" :class="$route.path == `/admin/orders` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/orders"><i data-feather="shopping-cart"></i> Orders</router-link>
              </li>
              <li v-if="$store.state.auth.permissions.Categories" :class="$route.path == `/admin/categories` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/categories"><i data-feather="grid"></i> Categories</router-link>
              </li>
              <li v-if="$store.state.auth.permissions.Marketing">
                <a data-bs-toggle="collapse" href="#us_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="gift"></i> Marketing</a>
                <div class="collapse" id="us_ma">
                  <ul class="collapse list-unstyled" id="us_ma">
                    <li v-if="$store.state.auth.permissions.FlashSales" :class="$route.path == `/admin/flashSale` ? `active` : ``">
                        <router-link to="/admin/flashSale"><i data-feather="zap"></i> Flash Sale</router-link>
                    </li>
                    <li v-if="$store.state.auth.permissions.RegularSales" :class="$route.path == `/admin/regularSale` ? `active` : ``">
                        <router-link to="/admin/regularSale"><i data-feather="award"></i> Regular Sale</router-link>
                    </li>
                  </ul>
                </div>
              </li>
              <li v-if="$store.state.auth.permissions.UserManagement">
                <a data-bs-toggle="collapse" href="#u_m" aria-expanded="false" aria-controls="collapseExample"><i data-feather="user"></i> User Management</a>
                <div class="collapse" id="u_m">
                  <ul class="collapse list-unstyled" id="u_m">
                    <li v-if="$store.state.auth.permissions.StaffManagement" :class="$route.path == `/admin/staffManagement` ? `active` : ``">
                        <router-link to="/admin/staffManagement"><i class="fa fa-users"></i> Staff Management</router-link>
                    </li>
                    <li v-if="$store.state.auth.permissions.RolePermission" :class="$route.path == `/admin/rolePermission` ? `active` : ``">
                        <router-link to="/admin/rolePermission"><i class="fa fa-key"></i> Role Permission</router-link>
                    </li>
                  </ul>
                </div>
              </li>
              <li v-if="$store.state.auth.permissions.SalesReport" :class="$route.path == `/admin/salesReport` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/salesReport"><i data-feather="trending-up"></i> Sales Report</router-link>
              </li>
              <li v-if="$store.state.auth.permissions.Customers" :class="$route.path == `/admin/customers` ? `active nav-item` : ``">
                <router-link class="nav-link a-admin" to="/admin/customers"><i data-feather="users"></i> Customers</router-link>
              </li>
            </ul>
          </nav>            
      </div>
      <div class="p-0" :style="(authenticated)?`width: 82%`:`width:200%`">
          <nav v-if="authenticated" class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: #fff; height: 55px">
              <div class="container-fluid">
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <!-- Left Side Of Navbar -->
                      <ul class="navbar-nav mr-auto">

                      </ul>

                      <!-- Right Side Of Navbar -->
                      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="px-5">
                                <router-link class="a-admin" to="/profile">
                                    <span class="fa fa-user-cog pr-2" style="font-size: 20px"></span><strong>  {{$store.state.auth.user.f_name}} {{$store.state.auth.user.l_name}}</strong>
                                </router-link>
                            </li>
                            <li>
                                <a @click="logout()" style="cursor:pointer">
                                <span class="fa fa-power-off" style="font-size: 20px"></span>
                                </a>
                            </li>
                        </ul>
                      </div>
                  </div>
              </div>
          </nav>
        <div class="row m-0 main_dis">
            <div class="col-md-12">   
                <router-view></router-view>             
            </div>
        </div>
        
      </div>
  </div>
</div>
</template>
<script>
 export default {
    data(){
      return{
        authenticated:false,
        permissions:{}
      }
    },
    mounted(){
      
      this.authenticated = this.$store.state.auth.authenticated
      this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permissions = this.$store.state.auth.permissions
        })
      feather.replace();
    },
    updated(){
      feather.replace();
    },
    computed: {
      togggle(){
          this.authenticated = this.$store.state.auth.authenticated; 
      },
      
    },
    methods:{
      logout(){
          axios.post("logout").then(response => { 
          window.location.replace("/home");
          })
          .catch(error => {
          console.log(error);
          });
      },
    }
  }
</script>