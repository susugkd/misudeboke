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
						<td class="title" width="100">标签：</td>
						<td>
							{{form.bq}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">操作明细：</td>
						<td>
							{{form.wb}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">参考链接：</td>
						<td>
							{{form.amount}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">单文件：</td>
						<td>
						<el-link v-if="form.file" style="font-size:13px;" :href="form.file" target="_blank">下载附件</el-link>
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
			axios.post(base_url+'/Batch/detail',this.info).then(res => {
				this.form = res.data.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
})
