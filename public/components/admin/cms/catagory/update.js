Vue.component('Update', {
	template: `
		<el-dialog title="修改" width="580px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" label-width="90px" >
			<el-tabs v-model="activeName">
				<el-tab-pane style="padding-top:10px"  label="基本信息" name="基本信息">
				<el-row>
					<el-col :span="24">
						<el-form-item label="所属父类" prop="pid">
							<treeselect v-if="show" :appendToBody="true" :default-expand-level="1" v-model="form.pid" :options="list" :normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属分类"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="栏目名称" prop="class_name">
							<el-input  v-model="form.class_name" autoComplete="off" clearable  placeholder="请输入栏目名称"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="栏目属性" prop="type">
							<el-radio-group v-model="form.type">
								<el-radio :label="1">频道</el-radio>
								<el-radio :label="2">列表</el-radio>
							</el-radio-group>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="17">
						<el-form-item label="栏目模板" prop="list_tpl">
							<el-input  v-model="form.list_tpl" autoComplete="off" clearable  placeholder="请输入栏目模板"></el-input>
						</el-form-item>
					</el-col>
					<el-col :span="7">
						<el-select :size="size" v-model="list_tpl" @change="setDefaultAbout" prop="list_tpl" clearable filterable placeholder="请选择">
							<el-option v-for="(item,index) in tpl_list" :key="index" :label="item.file" :value="item.name"></el-option>
						</el-select>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="17">
						<el-form-item label="内容模板" prop="detail_tpl">
							<el-input  v-model="form.detail_tpl" autoComplete="off" clearable  placeholder="请输入内容模板"></el-input>
						</el-form-item>
					</el-col>
					<el-col :span="7">
						<el-select :size="size" v-model="detail_tpl" @change="setDefaultDetail" prop="detail_tpl" clearable filterable placeholder="请选择">
							<el-option v-for="(item,index) in tpl_list" :key="index" :label="item.file" :value="item.name"></el-option>
						</el-select>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="栏目图片" prop="pic">
							<Upload v-if="show" size="small"  upload_type="2" file_type="image" :image.sync="form.pic"></Upload>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="绑定模型" prop="module_id">
							<el-select style="width:100%" :size="size" v-model="form.module_id" clearable filterable placeholder="请选择模型">
								<el-option v-for="(item,index) in module_list" :key="index" :label="item.title" :value="item.menu_id"></el-option>
							</el-select>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="上传配置" prop="id">
							<el-select style="width:100%" :size="size" v-model="form.upload_config_id" clearable filterable placeholder="请选择模型">
								<el-option v-for="(item,index) in upload_config_list" :key="index" :label="item.title" :value="item.id"></el-option>
							</el-select>
						</el-form-item>
					</el-col>
				</el-row>
				</el-tab-pane>
				<el-tab-pane style="padding-top:10px"  label="拓展信息" name="拓展信息">
				<el-row>
					<el-col :span="24">
						<el-form-item label="副标题" prop="subtitle">
							<el-input  v-model="form.subtitle" autoComplete="off" clearable  placeholder="请输入副标题"></el-input>
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
							<el-input type="textarea" v-model="form.description" autoComplete="off" clearable  placeholder="请输入描述"></el-input>
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
						<el-form-item label="排序号" prop="sortid">
							<el-input-number autoComplete="off" v-model="form.sortid" clearable placeholder="排序号"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="文件路径" prop="filepath">
							<el-input  v-model="form.filepath" autoComplete="off" clearable  placeholder="请输入文件路径"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="24">
						<el-form-item label="生成文件名" prop="filename">
							<el-input  v-model="form.filename" autoComplete="off" clearable  placeholder="请输入生成文件名"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				</el-tab-pane>
			</el-tabs>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button :size="size" :loading="loading" type="primary" @click="submit" >
					<span v-if="!loading">确 定</span>
					<span v-else>提 交 中...</span>
				</el-button>
				<el-button :size="size" @click="closeForm">取 消</el-button>
			</div>
		</el-dialog>
	`
	,
	components:{
		'treeselect':VueTreeselect.Treeselect,
	},
	props: {
		show: {
			type: Boolean,
			default: false
		},
		size: {
			type: String,
			default: false
		},
		info: {
			type: Object,
		},
		list:{
			type:Array,
		},
		tpl_list:{
			type:Array
		},
		module_list:{
			type:Array
		},
		upload_config_list:{
			type:Array
		}
	},
	data(){
		return {
			form: {
				class_name:'',
				type:1,
				status:1,
				list_tpl:'',
				detail_tpl:'',
				pic:'',
				module_id:'',
				upload_config_id:'',
				subtitle:'',
				keyword:[],
				description:'',
				jumpurl:'',
				filepath:'',
				filename:'',
				pid:null,
			},
			loading:false,
			list_tpl:'',
			detail_tpl:'',
			activeName:'基本信息',
			rules: {
				class_name:[
					{required: true, message: '栏目名称不能为空', trigger: 'blur'},
				],
			}
		}
	},
	
	methods: {
		open(){
			this.form = this.info
			if(this.info.pid == '0' ){
				this.$delete(this.info,'pid')
			}
			this.setDefaultVal('keyword')
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Cms.Catagory/update',this.form).then(res => {
						if(res.data.status == 200){
							this.$message({message: '操作成功', type: 'success'})
							this.$emit('refesh_list')
							this.closeForm()
						}else{
						    this.loading = false
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
				id: node.class_id,
				label: node.class_name,
				children: node.children
			}
		},
		setDefaultAbout(){
			this.form.list_tpl = this.list_tpl
		},
		setDefaultDetail(){
			this.form.detail_tpl = this.detail_tpl
		},
		setDefaultVal(key){
			if(this.form[key] == null || this.form[key] == ''){
				this.form[key] = []
			}
		},
		closeForm(){
			this.$emit('update:show', false)
			this.loading = false
			if (this.$refs['form']!==undefined) {
				this.$refs['form'].resetFields()
			}
		},
	}
})
