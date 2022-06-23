Vue.component('Add', {
	template: `
		<el-dialog title="添加" width="600px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" :label-width=" ismobile()?'90px':'16%'">
				<el-row >
					<el-col :span="24">
						<el-form-item label="标题" prop="title">
							<el-input  v-model="form.title" autoComplete="off" clearable  placeholder="请输入标题"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="标签" prop="bq">
							<Tag v-if="show" :tag_list.sync="form.bq"></Tag>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="操作明细" prop="wb">
							<el-input  type="textarea" autoComplete="off" v-model="form.wb"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入文本域"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="参考链接" prop="amount">
							<el-input controls-position="right" autoComplete="off" v-model="form.amount" clearable :min="0" placeholder="参考链接"/>
						</el-form-item>
					</el-col>
				</el-row>
			<el-row >
					<el-col :span="24">
						<el-form-item label="单文件" prop="file">
							<Upload v-if="show" size="small" file_type="file"  :file.sync="form.file"></Upload>
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
				title:'',
				sex:1,
				status:1,
				amount:'',
				wb:'',
				file:'',
				bq:[],
			},
			loading:false,
			rules: {
			}
		}
	},
	methods: {
		open(){
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Batch/add',this.form).then(res => {
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
		closeForm(){
			this.$emit('update:show', false)
			this.loading = false
			if (this.$refs['form']!==undefined) {
				this.$refs['form'].resetFields()
			}
		},
	}
})
