Vue.component('Add', {
	template: `
		<el-dialog title="添加" width="600px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" :label-width=" ismobile()?'90px':'16%'">
				<el-row >
					<el-col :span="24">
						<el-form-item  label="公司名称" prop="company_name">
							<el-input   v-model="form.company_name" autoComplete="off" clearable  placeholder="请输入公司名称"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item  label="联系人" prop="contacts">
							<el-input  v-model="form.contacts" autoComplete="off" clearable  placeholder="请输入联系人"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item  label="联系电话" prop="phone">
							<el-input  v-model="form.phone" autoComplete="off" clearable  placeholder="请输入联系电话"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="描述" prop="description">
							<el-input :autosize="{ minRows: 2, maxRows: 4}" type="textarea" v-model="form.description" autoComplete="off" clearable  placeholder="请输入公司简介"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				
				
				
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
			default: 'small'
		},
	},
	data(){
		return {
			form: {
				
			},
			pids:[],
			loading:false,
			rules: {
				company_name:[
					{required:true,message:'请输入公司名称',trigger: 'blur'},
				],
				contacts:[
					{required:true,message:'请输入联系人',trigger: 'blur'},
				],
				phone:[
					{required:true,message:'请输入联系电话',trigger: 'blur'},
				],
			}
		}
	},
	watch:{
		// show(val){
		// 	if(val){
		// 		axios.post(base_url + '/Company/getFieldList').then(res => {
		// 			if(res.data.status == 200){
		// 				this.pids = res.data.data.pids
		// 			}
		// 		})
		// 	}
		// }
	},
	methods: {
		open(){
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Company/add',this.form).then(res => {
						if(res.data.status == 200){
							this.$message({message: res.data.msg, type: 'success'})
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
				id: node.val,
				label: node.key,
				children: node.children
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
