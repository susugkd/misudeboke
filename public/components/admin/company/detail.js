Vue.component('Detail', {
	template: `
		<el-dialog title="查看详情" width="600px" @open="open" class="icon-dialog" :visible.sync="show" :before-close="closeForm" append-to-body>
			<table cellpadding="0" cellspacing="0" class="table table-bordered" align="center" width="100%" style="word-break:break-all; margin-bottom:50px;  font-size:13px;">
				<tbody>
					<tr>
						<td class="title" width="100">公司名称：</td>
						<td>
							{{form.company_name}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">联系人：</td>
						<td>
							{{form.contacts}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">联系电话：</td>
						<td>
							{{form.phone}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">描述：</td>
						<td>
							{{form.description}}
						</td>
					</tr>
					
					
				</tbody>
			</table>
		</el-dialog>
	`
	,
	props: {
		show: {
			type: Boolean,
			default: true
		},
		size: {
			type: String,
			default: 'mini'
		},
		info: {
			type: Object,
		},
	},
	data() {
		return {
			form:{
			},
		}
	},
	methods: {
		open(){
			axios.post(base_url+'/Company/detail',this.info).then(res => {
				this.form = res.data.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
})
