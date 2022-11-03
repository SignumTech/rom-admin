<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Add Product</strong></h5>
    </div> 
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <div class="bg-secondary rounded p-2" style="height: 150px">
                                        <h5 class="text-center pt-2"><span class="fa fa-camera"></span></h5>
                                        <h6 class="text-center pt-1"><strong>1080 X 1440</strong></h6>
                                        <label class="text-center text-white d-block m-auto fs-5 pt-2 fa fa-plus mt-2" style="cursor:pointer">
                                            <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event,index)" style="display: none">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9" style="min-height:75vh">
                            <div class="row h-100">
                                <div class="col-md-12 align-self-center mt-2 h-100">
                                    <div class="bg-secondary rounded p-2 h-100">
                                        <h5 class="text-center pt-2"><span class="fa fa-camera"></span></h5>
                                        <h5 class="text-center pt-1"><strong>1080 X 1440</strong></h5>
                                        <label class="text-center text-white d-block m-auto fs-5 pt-2 fa fa-plus mt-2" style="cursor:pointer">
                                            <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event,index)" style="display: none">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="#" @submit.prevent="addIdentity">
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
                        <div v-if="!identityAdded"  class="row mx-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-sm float-end">Next <span class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </form>
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
            productIdentity:{
                p_name: "",
                cat_id: "",
                description: "",
                price: ""
            },
        }
    }
}
</script>