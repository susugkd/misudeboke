Vue.component('Add', {
	template: `
		<div>
		<el-form :size="size" ref="form" :model="form" :rules="rules" label-width="90px">
			<el-tabs v-model="activeName">
				<el-tab-pane style="padding-top:10px"  label="基本信息" name="基本信息">
					<el-row>
						<el-col :span="24">
							<el-form-item label="文章标题" prop="title">
								<el-input  v-model="form.title" autoComplete="off" clearable  placeholder="请输入文章标题"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="所属栏目" prop="type">
								<treeselect @select="selectMenu" :default-expand-level="1" v-model="form.class_id" :options="catagory_list" :normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属栏目"/>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row >
						<el-col :span="22">
							<el-form-item label="缩略图" prop="pic">
								<Upload size="small" upload_type="2" :upload_config_id="upload_config_id" file_type="image" :image.sync="form.pic"></Upload>
							</el-form-item>
						</el-col>
						<el-col :span="2" class="hidden-md-and-down">
							<el-checkbox @change="setPic" style="margin-top:5px; margin-left:-120px;" v-model="set_pic_status">提取内容第一张图作为缩略图</el-checkbox>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="内容详情" prop="detail">
								<wang-editor :upload_config_id="upload_config_id"  :wangcontent.sync="form.detail"></wang-editor>
							</el-form-item>
						</el-col>
					</el-row>
					<Extend v-if="extendList" :issubmit="issubmit" :isClear.sync="isClear" :extend_data.sync="extendData" :upload_config_id="upload_config_id" :extend_list="extendList"></Extend>
				</el-tab-pane>
				<el-tab-pane style="padding-top:10px"  label="拓展信息" name="拓展信息">
					<el-row>
						<el-col :span="24">
							<el-form-item label="推荐位" prop="position">
								<el-checkbox-group v-model="form.position">
									<el-checkbox v-for="(item,i) in position_list" :key="i" :label="item.val.toString()">{{item.key}}</el-checkbox>
								</el-checkbox-group>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="副标题" prop="subtitle">
								<el-input  v-model="form.subtitle" autoComplete="off" clearable  placeholder="请输入副标题"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="跳转地址" prop="jumpurl">
								<el-input  v-model="form.jumpurl" autoComplete="off" clearable  placeholder="请输入跳转地址"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="作者" prop="author">
								<el-input  v-model="form.author" autoComplete="off" clearable  placeholder="请输入作者"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="关键词" prop="keyword">
								<Tag :tagList.sync="form.keyword"></Tag>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="描述" prop="description">
								<el-input  type="textarea" autoComplete="off" v-model="form.description"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入描述"/>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="排序号" prop="sortid">
								<el-input-number autoComplete="off" v-model="form.sortid" clearable placeholder="排序号"/>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row>
						<el-col :span="24">
							<el-form-item label="浏览量" prop="views">
								<el-input-number autoComplete="off" v-model="form.views" clearable placeholder="浏览量"/>
							</el-form-item>
						</el-col>
					</el-row>
				</el-tab-pane>
			</el-tabs>
			<el-form-item>
				<el-button size="small" type="primary" @click="submit">保存设置</el-button>
				<el-button :size="size" icon="el-icon-back" @click="back">返回</el-button>
			</el-form-item>
		</el-form>
	</div>
	`
	,
	components:{
		'treeselect':VueTreeselect.Treeselect,
	},
	props: {
		catagory_list:{
			type:Array,
		},
		position_list:{
			type:Array,
		},
		size: {
			type: String,
			default: 'small'
		},
	},
	data(){
		return {
			form: {
				title:'',
				pic:'',
				detail:'',
				status:1,
				position:[],
				jumpurl:'',
				create_time:'',
				keyword:'',
				description:'',
				views:'',
				author:'',
				subtitle:'',
			},
			isClear:false,
			loading:false,
			activeName:'基本信息',
			extendList:[],
			extendData:{},
			issubmit:false,
			upload_config_id:0,
			set_pic_status:false,
			rules: {
				title: [{ required: true, message: '文章标题不能为空', trigger: 'blur' }],
				class_id: [{ required: true, message: '文章栏目不能为空', trigger: 'blur' }],
            },
		}
	},
	mounted () {
		this.setDefaultVal('keyword')
	},
	methods: {
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					let formData = Object.assign(this.form, this.extendData)
					this.issubmit = true
					axios.post(base_url+'/Cms.Content/add',formData).then(res => {
						if(res.data.status == 200){
							this.$message({message: '操作成功', type: 'success'})
							this.$emit('refesh_list')
							this.closeForm()
						}else{
						    this.$message.error(res.data.msg)
						}
					}).catch(()=>{
						this.loading = false
					})
				}
			})
		},
		normalizer(node) {
			if (node.children && !node.children.length) {
				delete node.children
			}
			return {
				id: node.val,
				label: node.key,
				children: node.children
			}
		},
		setDefaultVal(key){
			if(this.form[key] == null || this.form[key] == ''){
				this.form[key] = []
			}
		},
		selectMenu(val){
			axios.post(base_url+'/Cms.Content/getExtend',{class_id:val.val}).then(res => {
				this.extendList = res.data.data
				this.upload_config_id = res.data.upload_config_id
			})
			this.isSubmit = true
		},
		back(){
			this.$emit('changepage')
		},
		setPic(val){
			if(val && this.form.detail){
				let result = /<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/.exec(this.form.detail)
				this.form.pic = result[1]
			}
		},
		closeForm(){
			this.loading = false
			if (this.$refs['form']!==undefined) {
				this.$refs['form'].resetFields()
			}
			this.isClear = true
			this.$emit('changepage')
		},
	}
})
