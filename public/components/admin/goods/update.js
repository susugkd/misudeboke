Vue.component('Update', {
	template: `
		<el-dialog title="修改" width="600px" class="icon-dialog" :visible.sync="show" @open="open" :before-close="closeForm" append-to-body>
			<el-form :size="size" ref="form" :model="form" :rules="rules" :label-width=" ismobile()?'90px':'16%'">
			<el-tabs v-model="activeName">
				<el-tab-pane style="padding-top:10px"  label="基本信息" name="基本信息">
				<el-row >
					<el-col :span="24">
						<el-form-item label="商品名称" prop="goods_name">
							<el-input  v-model="form.goods_name" autoComplete="off" clearable  placeholder="请输入商品名称"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="所属分类" prop="type">
							<treeselect v-if="show" :appendToBody="true" :default-expand-level="1" v-model="form.class_id" :options="class_ids" :normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属分类"/>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="供应商" prop="type">
							<el-select style="width:100%" v-model="form.supplier_id" filterable clearable placeholder="请选择">
								<el-option v-for="(item,i) in supplier_ids" :key="i" :label="item.key" :value="item.val"></el-option>
							</el-select>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="封面图" prop="pic">
							<Upload v-if="show" size="small"  upload_type="2" file_type="image" :image.sync="form.pic"></Upload>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="销售价" prop="sale_price">
							<el-input  v-model="form.sale_price" autoComplete="off" clearable  placeholder="请输入销售价"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="图集" prop="images">
							<Upload v-if="show" size="small"  file_type="images" :images.sync="form.images"></Upload>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="内容详情" prop="detail">
							<wang-editor v-if="show"   :wangcontent.sync="form.detail"></wang-editor>
						</el-form-item>
					</el-col>
				</el-row>
				</el-tab-pane>
				<el-tab-pane style="padding-top:10px"  label="拓展信息" name="拓展信息">
				<el-row >
					<el-col :span="24">
						<el-form-item label="状态" prop="status">
							<el-switch :active-value="1" :inactive-value="0" v-model="form.status"></el-switch>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="产地" prop="cd">
							<el-input  v-model="form.cd" autoComplete="off" clearable  placeholder="请输入产地"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
				<el-row >
					<el-col :span="24">
						<el-form-item label="库存" prop="store">
							<el-input  v-model="form.store" autoComplete="off" clearable  placeholder="请输入库存"></el-input>
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
				<el-row >
					<el-col :span="24">
						<el-form-item label="发布时间" prop="create_time">
							<el-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="date" v-model="form.create_time" clearable placeholder="请输入发布时间"></el-date-picker>
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
			default: 'small'
		},
		info: {
			type: Object,
		},
	},
	data(){
		return {
			form: {
				goods_name:'',
				supplier_id:'',
				pic:'',
				sale_price:'',
				images:[],
				status:1,
				cd:'',
				store:'',
				create_time:'',
				detail:'',
				user_id:'',
			},
			class_ids:[],
			supplier_ids:[],
			loading:false,
			activeName:'基本信息',
			rules: {
			}
		}
	},
	watch:{
		show(val){
			if(val){
				axios.post(base_url + '/Goods/getFieldList').then(res => {
					if(res.data.status == 200){
						this.class_ids = res.data.data.class_ids
						this.supplier_ids = res.data.data.supplier_ids
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
			this.setDefaultVal('images')
			this.form.create_time = parseTime(this.form.create_time)
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					axios.post(base_url + '/Goods/update',this.form).then(res => {
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
		setDefaultVal(key){
			if(this.form[key] == null || this.form[key] == ''){
				this.form[key] = []
			}
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
