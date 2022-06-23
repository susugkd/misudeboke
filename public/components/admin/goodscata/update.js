Vue.component('Update', {
	template: `
		<el-dialog title="修改" width="600px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" :label-width=" ismobile()?'90px':'16%'">
				<el-row >
					<el-col :span="24">
						<el-form-item label="分类名称" prop="class_name">
							<el-input  v-model="form.class_name" autoComplete="off" clearable  placeholder="请输入分类名称"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="所属父类" prop="type">
							<treeselect v-if="show" :appendToBody="true" :default-expand-level="1" v-model="form.pid" :options="pids" :normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属父类"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="状态" prop="status">
							<el-switch :active-value="1" :inactive-value="0" v-model="form.status"></el-switch>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="排序" prop="sortid">
							<el-input-number controls-position="right" style="width:200px;" autoComplete="off" v-model="form.sortid" clearable :min="0" placeholder="排序"/>
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
		info: {
			type: Object,
		},
	},
	data(){
		return {
			form: {
				class_name:'',
				status:1,
			},
			pids:[],
			loading:false,
			rules: {
			}
		}
	},
	watch:{
		show(val){
			if(val){
				axios.post(base_url + '/Goodscata/getFieldList').then(res => {
					if(res.data.status == 200){
						this.pids = res.data.data.pids
					}
				})
			}
		}
	},
	methods: {
		open(){
			this.form = this.info
			if(this.info.pid == '0' ){
				this.$delete(this.info,'pid')
			}
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Goodscata/update',this.form).then(res => {
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
