Vue.component('Extend', {
	template: `
		<div>
        <el-row v-for="(item,i) in extend_list" :key="i">
            <el-col v-if="item.type == 1" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-input v-model="form[item.field]" autoComplete="off" clearable  :placeholder="'请输入'+item.title"></el-input>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 2" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-select style="width:100%" :size="size" v-model="form[item.field]" clearable filterable placeholder="请选择">
						<el-option v-for="(vo,index) in JSON.parse(item.item_config)" :key="index" :label="vo.key" :value="['varchar','text','longtext'].includes(item.datatype)?vo.val:parseInt(vo.val)"></el-option>
					</el-select>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 3" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <el-select multiple style="width:100%" v-model="form[item.field]" filterable clearable placeholder="请选择">
                        <el-option v-for="(vo,index) in JSON.parse(item.item_config)" :key="index" :label="vo.key" :value="vo.val"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 4" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-radio-group v-model="form[item.field]">
                        <el-radio v-for="(vo,index) in JSON.parse(item.item_config)" :key="index" :label="['varchar','text','longtext'].includes(item.datatype)?vo.val:parseInt(vo.val)">{{vo.key}}</el-radio>
                    </el-radio-group>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 6" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-switch :active-value="1" :inactive-value="0" v-model="form[item.field]"></el-switch>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 8" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-input type="textarea" v-model="form[item.field]" autoComplete="off" clearable  :placeholder="'请输入'+item.title"></el-input>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 9" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-date-picker type="date" v-model="form[item.field]" autoComplete="off" clearable  :placeholder="'请输入'+item.title"></el-date-picker>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 10" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
					<el-date-picker type="daterange" v-model="form[item.field]" clearable range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
		        </el-form-item>
            </el-col>
            <el-col v-if="item.type == 13" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <Upload :size="size" :upload_type="item.upload_type.toString()" file_type="image" :upload_config_id="upload_config_id" :image.sync="form[item.field]"></Upload>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 14" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <Upload :size="size"  file_type="images"  :upload_config_id="upload_config_id" :images.sync="form[item.field]"></Upload>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 15" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <Upload :size="size" file_type="file"  :file.sync="form[item.field]"></Upload>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 16" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <Upload :size="size" file_type="files" :files.sync="form[item.field]"></Upload>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 17" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <el-input-number controls-position="right" style="width:200px;" autoComplete="off" v-model="form[item.field]" clearable :min="0" placeholder="计数器"/>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 18" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <Tag :tag_list.sync="form[item.field]"></Tag>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 19" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <el-slider v-model="form[item.field]"></el-slider>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 20" :span="23">
                <el-form-item :label="item.title" :prop="item.field">
                    <el-input v-model="form[item.field]" autoComplete="off" clearable placeholder="请输入颜色选择器"/>
                </el-form-item>
            </el-col>
            <el-col v-if="item.type == 20" :span="1">
                <el-color-picker size="small" v-model="form[item.field]" show-alpha></el-color-picker>
            </el-col>
            <el-col v-if="item.type == 21" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <key-data :item.sync="form[item.field]"></key-data>
                </el-form-item>
            </el-col>
			<el-col v-if="item.type == 22" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <shengshiqu :checkstrictly="{ checkStrictly: false }" :type="1" :treeoption.sync="form[item.field]"></shengshiqu>
                </el-form-item>
            </el-col>
            <el-col  v-if="item.type == 23" :span="22">
                <el-form-item :label="item.title" class="map" :prop="item.field">
                    <el-input type="textarea"  v-model="form[item.field]" placeholder="请选择坐标位置" v-on:click.native="bddtDialogStatus = true" readonly clearable></el-input>
                </el-form-item>
                <baidu-map v-if="bddtDialogStatus" :show.sync="bddtDialogStatus" :address_detail.sync="form[item.field]"></baidu-map>
            </el-col>
            <el-col v-if="item.type == 23" :span="1">
                <div class="el-input-group__append" @click="bddtDialogStatus = true" style="height:49px;background-color:#fff;padding:0 13px;cursor:pointer">
                    <i style="font-size:20px" class="el-icon-location"></i>
                </div>
            </el-col>
            <el-col v-if="item.type == 28" :span="22">
                <el-form-item :label="item.title" class="map" :prop="item.field">
                    <el-input type="textarea"  v-model="form[item.field]" placeholder="请选择坐标位置" v-on:click.native="txdtDialogStatus = true" readonly clearable></el-input>
                </el-form-item>
                <tx-map v-if="txdtDialogStatus" :show.sync="txdtDialogStatus" :address_detail.sync="form[item.field]"></tx-map>
            </el-col>
            <el-col v-if="item.type == 28" :span="1">
                <div class="el-input-group__append" @click="txdtDialogStatus = true" style="height:49px;background-color:#fff;padding:0 13px;cursor:pointer">
                    <i style="font-size:20px" class="el-icon-location"></i>
                </div>
            </el-col>
            <el-col v-if="item.type == 24" :span="22">
                <el-form-item :label="item.title" class="map" :prop="item.field">
                    <el-input type="textarea" v-model="form[item.field]" placeholder="请选择坐标位置" v-on:click.native="gddtDialogStatus = true" readonly clearable></el-input>
                </el-form-item>
                <gaode-map v-if="gddtDialogStatus" :show.sync="gddtDialogStatus" :address_detail.sync="form[item.field]"></gaode-map>
            </el-col>
            <el-col v-if="item.type == 24" :span="1">
                <div class="el-input-group__append" @click="gddtDialogStatus = true" style="height:49px;background-color:#fff;padding:0 13px;cursor:pointer">
                    <i style="font-size:20px" class="el-icon-location"></i>
                </div>
            </el-col>
            <el-col v-if="item.type == 25" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <wang-editor :isClear="isClear" :upload_config_id="upload_config_id" :wangcontent.sync="form[item.field]"></wang-editor>
                </el-form-item>
            </el-col>
			<el-col v-if="item.type == 26" :span="24">
                <el-form-item :label="item.title" :prop="item.field">
                    <tinymce :upload_config_id="upload_config_id" :content.sync="form[item.field]"></tinymce>
                </el-form-item>
            </el-col>
		</el-row>
    </div>
	`
	,
	components:{
	},
	props: {
		extend_list: {
			type: Array,
        },
        extend_data:{
            type:Object
        },
        upload_config_id:{
            type:Number,
        },
        isClear:{
            type:Boolean
        },
        issubmit:{
            type:Boolean
        },
    },
    watch: {
        extend_data(val){
            this.form = val
        },
        extend_list(val){
            val.forEach(item=>{
                if(item.type == 21){
                   this.$set(this.extend_data,item.field,[{}])
                }
            })
        },
        isClear(val){
            if(val){
                this.form = {}
            }
        },
        issubmit(val) {
            if(val){
                this.$emit('update:extend_data',this.form)
            }
        },
    },
    data() {
        return {
            form: this.extend_data,
            size:'small',
            bddtDialogStatus:false,
			txdtDialogStatus:false,
			gddtDialogStatus:false,
        }
    },
    mounted () {
        if(this.extend_list.length > 0){
            this.extend_list.forEach(item=>{
                if([3,5,14,16,18,22].includes(item.type)){
                    this.setDefaultVal(item.field)
                }
                if(item.type == 21 && !this.extend_data[item.field]){
                   this.$set(this.extend_data,item.field,[])
                }
            })
        }
    },
    methods: {
        setDefaultVal(key){
			if(this.form[key] == null || this.form[key] == ''){
				this.form[key] = []
			}
		},
    },
})
