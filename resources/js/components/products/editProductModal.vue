<template>
<div class="row m-0">
    <div class="col-md-12 py-3">
        <form action="#" @submit.prevent="updateIdentity">
            <div class="row m-0">
                <div class="col-md-6">
                    <label for="">Product Name</label>
                    <input required v-model="productIdentity.p_name" type="text" class="form-control" placeholder="Product Name">
                </div>
                <div class="col-md-6">
                    <label for="">Category</label>
                    <select required v-model="productIdentity.cat_id" class="form-select" id="">
                        <option value=""></option>
                        <optgroup v-for="cat in categories" :key="cat.id" :label="cat.parent">
                            <option v-for="sub in cat.children" :key="sub.id" :value="sub.id">{{sub.cat_name}}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="">Price</label>
                    <input required v-model="productIdentity.price" type="number" class="form-control" placeholder="Price">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="">Brand (optional)</label>
                    <select class="form-select" id="">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">Product description</label>
                    <textarea required v-model="productIdentity.description" class="form-control" id="" cols="20" rows="3"></textarea>
                </div>
            </div><hr>
            <div class="row mx-0">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm form-control">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>
<script>
import axios from 'axios';

export default {
    props:['product'],
    data(){
        return{
            productIdentity:{},
            categories:{}
        }
    },
    mounted(){
        this.productIdentity = this.product
        this.getSubCategories()
    },
    methods:{
        async getSubCategories(){
            await axios.get('/chooseSubCategories')
            .then( response =>{
                this.categories = response.data
            })
        },
        async updateIdentity(){
            await axios.put('/products/'+this.productIdentity.id, this.productIdentity)
            .then( response =>{
                this.$emit('close')
            })
        }
    }
}
</script>