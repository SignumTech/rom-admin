import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
//////////////////////admin////////////////////////////////////
import adminDash from './components/dashboard/adminDash.vue'
import categories from './components/categories/categories.vue'
import products from './components/products/products.vue'
import newProduct from './components/products/newProduct.vue'
import addProducts from './components/products/addProduct.vue'
import editProducts from './components/products/editProduct.vue'
import orders from './components/orders/orders.vue'
import adminOrderDetails from './components/orders/orderDetails.vue'
import flashSale from './components/flash_sales/flashSale.vue'
import flashSaleDetail from './components/flash_sales/flashSaleDetails.vue'
import customers from './components/customers/customers.vue'
import salesReport from './components/sales_report/salesReport.vue'
///////////////////user management////////////////////////////////
import rolePermission from './components/user_management/rolePermission.vue'
import staffManagement from './components/user_management/staffManagement.vue'
///////////////////////////////////////////////////////////////
import signin from './components/auth/signin.vue'
import otp from './components/auth/otp.vue'
import forgetPassword from './components/auth/forgetPassword.vue'
import resetOTP from './components/auth/resetOTP.vue'
import resetPassword from './components/auth/resetPassword.vue'


Vue.use(Router)

const routes = [
    {
        path: '/admin/salesReport',
        component: salesReport,
        name: 'SalesReport',
        props: true
    },
    {
        path: '/admin/customers',
        component: customers,
        name: 'Customrs',
        props: true
    },
    {
        path: '/admin/rolePermission',
        component: rolePermission,
        name: 'RolePermission',
        props: true
    },
    {
        path: '/admin/staffManagement',
        component: staffManagement,
        name: 'StaffManagement',
        props: true
    },
    {
        path: '/admin/flashSale',
        component: flashSale,
        name: 'FlashSale'
    },
    {
        path: '/admin/flashSaleDetail/:id',
        component: flashSaleDetail,
        name: 'FlashSaleDetail'
    },
    {
        path: '/admin/adminDash',
        component: adminDash,
        name: 'AdminDash'
    },
    {
        path: '/admin/categories',
        component: categories,
        name: 'Categories'
    },
    {
        path: '/admin/products',
        component: products,
        name: 'Products'
    },
    {
        path: '/admin/orders',
        component: orders,
        name: 'Orders'
    },
    ,
    {
        path: '/admin/orderDetails/:id',
        component: adminOrderDetails,
        name: 'AdminOrderDetails'
    },
    {
        path: '/admin/addProducts',
        component: addProducts,
        name: 'AddProducts',
        props: true
    },
    {
        path: '/admin/editProduct/:id',
        component: editProducts,
        name: 'EditProduct',
        props: true
    },
    {
        path: '/signin',
        component: signin,
        name: 'Signin'
    },  
    {
        path: '/otp',
        component: otp,
        name: 'Otp',
        props: true
    },  
    {
        path: '/forgetPassword',
        component: forgetPassword,
        name: 'ForgetPassword'
    },     
    ,     
    {
        path: '/resetOTP',
        component: resetOTP,
        name: 'ResetOTP',
        props: true
    }, 
    {
        path: '/resetPassword',
        component: resetPassword,
        name: 'ResetPassword',
        props: true
    },   
    {
        path: '/',
        component: adminDash,
        name: 'Dashboard',
        props: true
        
    },    
    {
        path: '/admin/newProduct',
        component: newProduct,
        name: 'NewProduct',
        props: true
        
    },  
    
    
]

export default new  Router({
    mode: 'history',
    routes
})
