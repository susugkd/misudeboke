Vue.component('Detail', {
	template: `
		<el-dialog title="查看详情" width="600px" @open="open" class="icon-dialog" :visible.sync="show" :before-close="closeForm" append-to-body>
			<table cellpadding="0" cellspacing="0" class="table table-bordered" align="center" width="100%" style="word-break:break-all; margin-bottom:50px;  font-size:13px;">
				<tbody>
					<tr>
						<td class="title" width="100">标题：</td>
						<td>
							{{form.title}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">计数器：</td>
						<td>
							{{form.jsq}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">标签：</td>
						<td>
							{{form.tags}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">滑块：</td>
						<td>
						<el-progress :percentage="form.hk"></el-progress>
						</td>
					</tr>
					<tr>
						<td class="title" width="100">颜色选择器：</td>
						<td>
							{{form.color}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">键值对：</td>
						<td>
							{{form.jzd}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">省市区联动：</td>
						<td>
							{{form.ssq}}
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
			axios.post(base_url+'/Other/detail',this.info).then(res => {
				this.form = res.data.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
})
