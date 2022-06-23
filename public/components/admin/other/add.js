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
						<el-form-item label="计数器" prop="jsq">
							<el-input-number controls-position="right" style="width:200px;" autoComplete="off" v-model="form.jsq" clearable :min="0" placeholder="计数器"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="标签" prop="tags">
							<Tag v-if="show" :tag_list.sync="form.tags"></Tag>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="滑块" prop="hk">
							<el-slider v-model="form.hk"></el-slider>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="23">
						<el-form-item label="颜色选择器" prop="color">
							<el-input v-model="form.color" autoComplete="off" clearable placeholder="请输入颜色选择器"/>
						</el-form-item>
					</el-col>
					<el-col :span="1">
						<el-color-picker size="small" v-model="form.color" show-alpha></el-color-picker>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="键值对" prop="jzd">
							<key-data v-if="show" :item.sync="form.jzd"></key-data>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="省市区联动" prop="ssq">
							<shengshiqu v-if="show" :checkstrictly="{ checkStrictly: false }" :type="1" :treeoption.sync="form.ssq"></shengshiqu>
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
				tags:[],
				color:'',
				jzd:[{}],
				ssq:[],
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
					axios.post(base_url + '/Other/add',this.form).then(res => {
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
			this.form.hk = 0
			this.form.jzd = [{}]
		},
	}
})
