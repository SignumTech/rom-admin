<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Add Product</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white shadow-sm rounded-1 p-3">
            <div class="row m-0 p-2 border-bottom">
                <div class="col-md-4 text-center">
                    <h6 v-if="tab == `Identity`" class="text-center m-0 p-2 bg-primary rounded-1 shadow-sm text-white"><strong><i class="fa fa-edit"></i> Product Identity</strong></h6>
                    <h6 v-if="tab != `Identity`" class="text-center m-0 p-2"><i class="fa fa-edit"></i> Product Identity</h6>
                </div>
                <div class="col-md-4 text-center">
                    
                    <h6 v-if="tab == `Colors`" class="text-center p-2 bg-primary rounded-1 shadow-sm text-white"><strong><i class="fa fa-palette"></i> Images, Colors, Sizes</strong></h6>
                    <h6 v-if="tab != `Colors`" class="text-center p-2"><i class="fa fa-palette"></i>  Colors and images</h6>
                </div>
                <div class="col-md-4 text-center">
                    <h6 v-if="tab == `Preview`" class="text-center p-2 bg-primary rounded-1 shadow-sm text-white"><strong><span  class="text-center px-2 py-1 bg-primary rounded shadow-sm text-white">3</span> Publish</strong></h6>
                    <h6 v-if="tab != `Preview`" class="text-center p-2"><i class="fa fa-check-circle"></i>  Publish</h6>
                </div>
            </div>
            <div  v-if="tab==`Identity`" class="row mx-0 mt-5">
                <div class="col-md-3"></div>
                <div class="col-md-6 px-0">
                    <form action="#" @submit.prevent="addIdentity">
                        <div class="row m-0">
                            <div class="col-md-6">
                                <label for="">Product Name</label>
                                <input required v-model="productIdentity.p_name" type="text" class="form-control" placeholder="Product Name">
                            </div>
                            <div class="col-md-6">
                                <label for="">Category</label>
                                <treeselect placeholder="Category" required v-model="productIdentity.cat_id" :disable-branch-nodes="true" :multiple="false" :options="categories" />
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
                    <div v-if="identityAdded" class="row mx-0">
                        <div class="col-md-12">
                            <button @click="displayColors()" class="btn btn-primary btn-sm float-end">Next <span class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                </div>
            </div>
            <div  v-if="tab == `Colors`">
                <div class="row mx-0 mt-3">
                    <div class="col-md-12">
                        <button @click="addColorModal()" class="btn btn-primary btn-sm float-end"><span class="fa fa-plus"></span> Add Colors</button>
                    </div>
                </div>
                <div v-if="!colorsAdded" class="row mx-0 mt-3">
                    <div class="col-md-12 ">
                        <div class="bg-secondary rounded-2 p-5 text-white">
                            
                            <h1 class="text-center"> <i class="fa fa-palette"></i> <i class="fa fa-paint-brush"></i></h1>
                            <h3 class="text-center"><strong>You havent added any colors!</strong></h3>
                            <h5 class="text-center"> Click on "Add colors" to start.</h5>
                        </div>
                    </div>
                </div>
                <div v-for="(color,index) in colors" :key="index" class="row mx-0 mt-3 mb-5">
                    
                    <div class="col-md-12">
                        <h6 class="m-0"><strong>Color:  <span class="shadow-sm rounded p-1 text-white" :style="{backgroundColor:color.color}">{{color.color}}</span> <span @click="editColorModal(color)" class="fa fa-edit ms-2"></span><span class="fa fa-trash-alt ms-2"></span></strong></h6>
                    </div>
                    <div class="col-md-4 mt-3 border-end">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5 class="m-0">Sizes and Quantities <span @click="addSizes(color.id)" class="fa fa-plus float-end" style="cursor:pointer"></span></h5>
                                <h5 class="mt-3" v-for="inv,index in color.inventory" :key="index">{{inv.size}} : {{inv.quantity}} <span @click="editSizes(inv)" class="fa fa-edit ms-3 fs-6"></span> <span @click="deleteSizes(inv.size_id)" class="fa fa-trash-alt ms-3 fs-6"></span></h5>
                            </div>
                        </div>   
                    </div>
                    <div class="col-md-8 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Pictures</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div v-for="(pic,picIndex) in color.images" :key="`im`+picIndex" class="col-md-3 align-self-center mt-2 ">
                                <div class="row m-0 show-add-btn">
                                    <div class="col-md-12 px-0">
                                        <img :src="`https://rom.signumdev.com/storage/products/`+pic.p_image" class="img img-fluid" alt="">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <span class="add-cart">
                                            <button type="button" @click="deleteImage(pic.id)" class="btn btn-light btn-sm rounded-1 shadow"><strong><span class="fa fa-trash-alt"></span> Delete</strong></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="picLoading" class="col-md-3 align-self-center mt-2" >
                                <div class="d-flex justify-content-center mt-5 mb-5">
                                    <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                                </div>
                            </div>
                            <div class="col-md-3 align-self-center mt-2" >
                                <div class="bg-secondary rounded p-2" style="height: 225px">
                                    <h5 class="text-center pt-2"><span class="fa fa-camera"></span></h5>
                                    <h5 class="text-center pt-1"><strong>1080 X 1440</strong></h5>
                                    <h6 class="text-center">Please choose image according to the aspect ratio</h6>
                                    <label class="text-center text-white d-block m-auto fs-5 pt-2 fa fa-plus mt-2" style="cursor:pointer">
                                        <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event,color.id)" style="display: none">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 mt-5">
                    <div class="col-md-12">
                        <h6 v-if="picturesMissing" class="text-danger text-center">"You must insert atleast one picture to every color"</h6>
                    </div>
                    <div class="col-md-12">
                        <h6 v-if="sizesMissing" class="text-danger text-center">"You must insert sizes and quantities to every color."</h6>
                    </div>
                    <div class="col-md-6">
                        <button @click="displayIdentity()" class="btn btn-primary btn-sm"><span class="fa fa-arrow-left"></span> Previous</button>
                    </div>
                    <div v-if="colorsAdded" class="col-md-6">
                        <button @click="saveColors()"  type="submit" class="btn btn-primary btn-sm float-end">Next <span class="fa fa-arrow-right"></span></button>
                    </div>
                </div>
            </div>
            <div v-if="tab == `Preview`">
                <div class="row mt-3 mx-0">
                    <div class="col-md-1 p-0">
                        <div v-for="(pic) in previewData[currentColor].images" :key="pic" class="row show-add-btn">
                            <div class="col-md-12">
                                <img class="img img-fluid mt-2" :src="`https://rom.signumdev.com/storage/products/`+pic.p_image" alt="">
                            </div>
                        </div>                  
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <img class="img img-fluid mt-2" :src="`https://rom.signumdev.com/storage/products/`+previewData[currentColor].images[0].p_image" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-0 mt-2"><strong>{{product.p_name}} </strong></h5>
                        <h6 class="mb-0 mt-2">{{product.description}}</h6>
                        <h2 class="mt-4"><strong>{{product.price}} Birr</strong></h2>
                        <hr class="mt-4">
                        <h5 class="mt-4"><strong>Colors</strong></h5>
                        <h5><span class="badge rounded-1 py-2 px-5 shadow m-1" :style="{backgroundColor:currentColor}">{{currentColor}}</span> </h5>
                        <h5>
                            <span @click="makeCurrentColor(color.color)" v-for="color,index in colors" :key="`color`+index" :class="(currentColor == color.color)?`hov-color badge rounded-1 p-2 shadow m-1 color-choice`:`hov-color badge rounded-1 p-2 shadow m-1`" :style="{backgroundColor:color.color}"><h5 class="m-0"></h5></span>
                            <!--<span @click="addColor()" class="fa fa-plus" style="cursor:pointer"></span>-->
                        </h5>
                        
                        <h5 class="mt-4"><strong>Sizes & Quantities</strong></h5>
                        <div v-for="size,index in previewData[currentColor].inventory" :key="`inv`+index" class="row">
                            <div class="col-md-2">
                                <label for="">Size</label>
                                <h5>{{size.size}}</h5>
                            </div>
                            <div class="col-md-2">
                                <label for="">Quantity</label>
                                <h5>{{size.quantity}}</h5>
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
                <div class="row mx-0 mt-5">
                    <div class="col-md-6">
                        <button @click="tab=`Colors`" class="btn btn-primary btn-sm"><span class="fa fa-arrow-left"></span> Previous</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addColorModalVue from './addColorModal.vue';
import addSizesModalVue from './addSizesModal.vue';
import editSizeVue from './editSize.vue';
import editColorModalVue from './editColorModal.vue';
export default {
    components:{
        Treeselect,
        PulseLoader
    },
    props:["type", "item"],
    beforeMount() {
        window.addEventListener("beforeunload", this.preventNav)
        this.$once("hook:beforeDestroy", () => {
        window.removeEventListener("beforeunload", this.preventNav);
        })
    },
    beforeRouteLeave(to, from, next) {
        if (this.isEditing) {
            if (!window.confirm("Leave without saving?")) {
                return;
            }
            else{
                this.deleteProduct()
            }
        }
        next();
    },
    data(){
        return{
            isEditing: false,
            previewData: {},
            picLoading:false,
            product:{},
            pictures:{main:""},
            colors:{},
            sizes:{},
            currentColor:'',
            categories:[],
            product_id:1,
            productIdentity:{
                p_name: "",
                cat_id: null,
                description: "",
                price: ""
            },
            colorData:[],
            identityAdded:false,
            picturesMissing:false,
            sizesMissing:false,
            colorsAdded:false,
            imagesAdded:false,
            sizesAdded:false,
            tab:"Identity",
        }
    },
    mounted(){
        if(this.type == 'edit'){
            this.productIdentity.p_name = this.item.p_name
            this.productIdentity.cat_id = this.item.cat_id
            this.productIdentity.description = this.item.description
            this.productIdentity.price = this.item.price
        }
        this.getSubCategories()
        feather.replace();
    },
    methods:{
        editColorModal(color){
            this.$modal.show(
                editColorModalVue,
                {color:color},
                {width:"400px",height:"auto"},
                { "closed" : this.getColors}
            )
        },
        async deleteSizes(id){
            var check = confirm("Are you sure you want to delete this size?")
            if(check){
                await axios.delete('/deleteSize/'+id)
                .then( response =>{
                    this.getColors()
                })
            }
            
        },
        editSizes(inv){
            this.$modal.show(
                editSizeVue,
                {inv:inv},
                {height:"auto", width:"500px"},
                {"closed":this.getColors}
            )
        },
        addSizes(color_id){
            this.$modal.show(
                addSizesModalVue,
                {color_id:color_id, product_id:this.product_id },
                {height:"auto", width:"500px"},
                {"closed":this.getColors}
            )
        },
        async getColors(){
            await axios.get('/getColorsData/'+this.product_id )
            .then( response =>{
                this.colors = response.data
            })
        },
        async deleteProduct(){
            await axios.delete('/products/'+this.product_id)
            .then( response =>{

            })
        },
        preventNav(event) {
            if (!this.isEditing) return
            event.preventDefault()
            event.returnValue = ""
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
            this.isEditing = false
            await axios.post('/publishProduct', {p_id:this.product_id})
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
        async editIdentity(){
            await axios.put('/products/'+this.item.id, this.productIdentity)
            .then( response =>{
                this.identityAdded = true
                this.product_id = response.data.id
                this.getPInventory()
                this.displayColors()
            })
        },
        async getPInventory(){
            await axios.get('/getColorInventory/'+this.product_id)
            .then( response =>{
                this.colorData = response.data
                if(this.colorData.length > 0){
                    this.colorsAdded = true
                }
                
            })
        },
        async deleteImage(id){
            await axios.delete('/deleteProductImage/'+id)
            .then( response =>{
                this.getColors()
            })
        },
        async saveColors(){
            
            this.validateSizes()
            this.validateImages()
            
            if(!this.picturesMissing && !this.sizesMissing){
                this.getPreviewData()
                
            }            

        },
        async getPreviewData(){
            await axios.get('/getPreviewData/'+this.product_id)
            .then( response =>{
                this.previewData = response.data
                this.currentColor = this.colors[0].color
                this.tab = 'Preview'
            })
        },
        validateSizes(){
            this.colors.forEach(color=>{
                
                if(color.inventory.length == 0){
                    this.sizesMissing = true
                    return false;
                }
                else{
                    this.sizesMissing = false
                    return true
                }
            })
        },
        validateImages(){
            this.colors.forEach(color=>{
                
                if(color.images.length == 0){
                    this.picturesMissing = true
                    return false;
                }
                else{
                    this.picturesMissing = false
                    return true
                }
            })
        },
        async updateColors(){
            await axios.post('/updateColors', {colorData:this.colorData, product_id:this.product_id})
            .then( response =>{
                this.product = response.data
                this.displayPreview()
                this.colorsAdded = true
            })
            .catch( error =>{
                if(error.response.status == 422){
                    this.picturesMissing = true
                }
            })  
        },
        async uploadPic($event,id){
            this.picLoading = true
            const data = new FormData();
            this.photo = $event.target.files[0];
            data.append('photo', this.photo);
            data.append('color_id', id);
            data.append('product_id', this.product_id);
            this.img_loading = true;
            await axios.post('/uploadProductPic', data)
            .then( response => {
                this.getColors()
                this.picLoading = false
            })
            .catch( error => {
                if (error.response.status == 422){
                    this.validationErrors = error.response.data.errors.photo;
                }
                this.picLoading = false;
            })
        },
        async addIdentity(){
            if(this.type == 'edit'){
                this.editIdentity()
            }
            else{
                await axios.post('/products', this.productIdentity)
                .then( response =>{
                    this.identityAdded = true
                    this.product_id = response.data.id
                    this.tab = 'Colors'
                    this.isEditing = true
                })                
            }

            
        },
        async getSubCategories(){
            await axios.get('/chooseSubCategories')
            .then( response =>{
                this.categories = response.data
            })
        },
        displayButtons(){
            this.colorsAdded = true
            this.getColors()
        },
        addColorModal(){
            this.$modal.show(
                addColorModalVue,
                {"product_id":this.product_id},
                {width:"400px",height:"auto"},
                { "closed" : this.displayButtons}
            );
        },
        async getProduct(){
            await axios.get('/products/'+this.product_id)
            .then( response =>{
                this.product = response.data
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
            await axios.get('/getInventory/'+this.product_id)
            .then( response =>{
                this.sizes = response.data
            })
        },
        makeCurrentColor(color){
            this.currentColor = color
        },
    }
}
</script>