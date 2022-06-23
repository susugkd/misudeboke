Vue.component('Update', {
	template: `
		<el-dialog title="修改" width="700px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" :label-width=" ismobile()?'90px':'16%'">
				<el-row >
					<el-col :span="24">
						<el-form-item label="普通" prop="a">
							<el-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" v-model="form.a" clearable placeholder="请输入普通"></el-date-picker>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="年月日" prop="b">
							<el-date-picker value-format="yyyy-MM-dd" type="date" v-model="form.b" clearable placeholder="请输入年月日"></el-date-picker>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="年" prop="year">
							<el-date-picker value-format="yyyy" type="year" v-model="form.year" clearable placeholder="请输入年"></el-date-picker>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="月" prop="month">
							<el-date-picker value-format="MM" type="month" v-model="form.month" clearable placeholder="请输入月"></el-date-picker>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="时分秒" prop="sfm">
							<el-time-picker value-format="HH:mm:ss" v-model="form.sfm" clearable placeholder="请输入时分秒"></el-time-picker>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="datetime格式" prop="d">
							<el-date-picker value-format="yyyy-MM-dd" type="date" v-model="form.d" clearable placeholder="请输入datetime格式"></el-date-picker>
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
		info: {
			type: Object,
		},
	},
	data(){
		return {
			form: {
				a:'',
				b:'',
				year:'',
				month:'',
				sfm:'',
				d:'',
			},
			loading:false,
			rules: {
			}
		}
	},
	methods: {
		open(){
			this.form = this.info
			this.form.a = parseTime(this.form.a)
			this.form.b = parseTime(this.form.b)
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Times/update',this.form).then(res => {
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
