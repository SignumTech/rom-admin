<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Edit Product</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded shadow-sm p-5">
            <div class="row m-0">
                <div class="col-md-1 p-0">
                    <div v-if="!noImage">
                        <div v-for="(pic,pic_index) in pictures[currentColor]" :key="pic" class="row show-add-btn">
                            <div class="col-md-12">
                                <img class="img img-fluid mt-2" :src="`/storage/productsThumb/`+pic" alt="">
                            </div>
                            <div class="col-md-12 text-center">
                                <span class="add-cart">
                                    <button v-if="pictures[currentColor].length > 1" type="button" @click="deleteImage(pic, pic_index)" class="btn btn-light btn-sm rounded-1 shadow"><strong><span class="fa fa-trash-alt"></span> Delete</strong></button>
                                </span>
                            </div>
                        </div> 
                    </div>
                    <div class="bg-secondary rounded p-2 mt-2" style="height: 125px">
                        <h6 class="text-center pt-2"><span class="fa fa-camera"></span></h6>
                        <h6 class="text-center pt-1"><strong>1080 X 1440</strong></h6>
                        <h6 v-if="!colorAdded" class="mt-2 text-center">add colors first!</h6>
                        <label v-if="colorAdded" class="text-center text-white d-block m-auto fs-5 pt-1 fa fa-plus mt-1" style="cursor:pointer">
                            <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event,index)" style="display: none">
                        </label>
                    </div>                   
                </div>
                <div v-if="!noImage" class="col-md-5">
                    <div class="row show-add-btn">
                        <div class="col-md-12">
                            <img class="img img-fluid mt-2" :src="`/storage/products/`+pictures[currentColor][0]" alt="">
                        </div>
                    </div>
                </div>
                <div v-if="noImage" class="col-md-5">
                    <div class="bg-secondary rounded p-2 mt-2" style="height: 100%">
                        <h6 class="text-center pt-2"><span class="fa fa-camera"></span></h6>
                        <h6 class="text-center pt-1"><strong>1080 X 1440</strong></h6>
                        <h6 v-if="!colorAdded" class="mt-2 text-center">You need to add colors first!</h6>
                        <label v-if="colorAdded" class="text-center text-white d-block m-auto fs-5 pt-1 fa fa-plus mt-1" style="cursor:pointer">
                            <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event)" style="display: none">
                        </label>
                    </div> 
                </div>
                <div class="col-md-6">
                    <h5 class="mb-0 mt-2"><strong>{{product.p_name}} <span @click="editProductModal()" class="fa fa-edit float-end" style="cursor:pointer"></span></strong></h5>
                    <h6 class="mb-0 mt-2">{{product.description}}</h6>
                    <h2 class="mt-4"><strong>{{product.price | numFormat}} Birr</strong></h2>
                    <hr class="mt-4">
                    <h5 class="mt-4"><strong>Colors</strong></h5>
                    <h5><span class="badge rounded-1 py-2 px-5 shadow m-1" :style="{backgroundColor:currentColor}">{{currentColor}}</span> 
                        <!--<span @click="addColor()" class="fa fa-plus me-3"></span> <span v-if="colorAdded" class="fa fa-edit me-3"></span> <span v-if="colorAdded" class="fa fa-trash-alt ml-2 me-3"></span>-->
                    </h5>
                    <h5>
                        <span @click="makeCurrentColor(color)" v-for="color in colors" :key="color.id" :class="(currentColor == color)?`hov-color badge rounded-1 p-2 shadow m-1 color-choice`:`hov-color badge rounded-1 p-2 shadow m-1`" :style="{backgroundColor:color}"><h5 class="m-0"></h5></span>
                        <!--<span @click="addColor()" class="fa fa-plus" style="cursor:pointer"></span>-->
                    </h5>
                    
                    <h5 class="mt-4"><strong>Sizes & Quantities <span v-if="colorAdded" @click="editSizes()" class="fa fa-edit" style="cursor:pointer"></span></strong></h5>
                    <div v-for="size in sizes[currentColor]" :key="size.id" class="row">
                        <div class="col-md-2">
                            <label for="">Size</label>
                            <h5>{{size['size']}}</h5>
                        </div>
                        <div class="col-md-2">
                            <label for="">Quantity</label>
                            <h5>{{size['quantity']}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button @click="saveDraft()" class="btn btn-warning form-control"><span class="fa fa-save"></span> SAVE AS DRAFT</button>
                        </div>
                        <div class="col-md-6">
                            <button @click="publishProduct()" class="btn btn-success form-control"><span class="fa fa-check-circle"></span> PUBLISH</button>
                        </div>
                    </div>
                </div>
            </div>            
        </div>

    </div>
</div>    
</template>
<script>
import addNewColorVue from './addNewColor.vue'
import editProductModalVue from './editProductModal.vue'
import editSizesModal from './editSizesModal.vue'
export default {
    props:["item"],
    data(){
        return{
            noImage:false,
            colorAdded:false,
            product:{},
            pictures:{main:""},
            productIdentity:{},
            colors:{},
            sizes:[],
            currentColor:'',
            validationErrors:""
        }
    },
    mounted(){
       this.getProduct()
       this.getInventory()
    },
    methods:{
        async uploadPic($event){
            this.picLoading = true
            const data = new FormData();
            this.photo = $event.target.files[0];
            this.color = this.currentColor;
            this.pd_id = this.product.id;
            data.append('photo', this.photo);
            data.append('color', this.color);
            data.append('product_id', this.pd_id);
            this.img_loading = true;
            await axios.post('/updateProductPic', data)
            .then( response => {
                this.getProduct()
                this.picLoading = false
            })
            .catch( error => {
                if (error.response.status == 422){
                    this.validationErrors = error.response.data.errors.photo;
                }
                this.picLoading = false;
            })
        },
        saveDraft(){
            this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Saving Draft Successful',
                    text: 'You have successfuly saved a draft!'
                });
                this.$router.push({name:'Products'})
        },
        async publishProduct(){
            await axios.post('/publishProduct', {p_id:this.product.id})
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Publishing Successful',
                    text: 'You have successfuly published your product!'
                });
                this.$router.push({name:'Products'})
            })
        },
        async deleteImage(pic, pic_index){
            await axios.post('/deleteProductImage', {pic:pic})
            .then( response =>{
                this.colorData[colorIndex].pictures.splice(picIndex,1)
            })
        },
        addColor(){
            this.$modal.show(
                addNewColorVue,
                {"colors":this.colors, "pictures":this.pictures, "sizes":this.sizes},
                {width:"300px",height:"auto"},
                {}
            )
        },
        editSizes(){
            this.$modal.show(
                editSizesModal,
                {"sizeData":this.sizes, "currentColor":this.currentColor, "product_id":this.product.id},
                {width:"300px",height:"auto"},
                {"closed" : this.getInventory}
            )
        },
        editProductModal(){
            this.$modal.show(
                editProductModalVue,
                {"product":this.product},
                {width:"400px",height:"auto"},
                {"closed" : this.getProduct}
            );
        },
        makeCurrentColor(color){
            this.currentColor = color
        },
        async getProduct(){
            await axios.get('/products/'+this.$route.params.id)
            .then( response =>{
                this.product = response.data
                if(JSON.parse(this.product.p_image).length == 0){
                    this.noImage = true
                }
                if(JSON.parse(response.data.color).length == 0){
                    this.colorAdded = false
                }
                else{
                    this.colorAdded = true
                }
                this.pictures = JSON.parse(this.product.p_image)
                this.colors = JSON.parse(response.data.color)
                this.currentColor = this.colors[0]
                this.productIdentity = {
                    p_name:response.data.p_name,
                    cat_id:response.data.cat_id,
                    price:response.data.price,
                    brand:response.data.brand_id,
                    description:response.data.description
                }
            })
        },
        async getInventory(){
            await axios.get('/getInventory/'+this.$route.params.id)
            .then( response =>{
                this.sizes = response.data
            })
        }
    }
}
</script>