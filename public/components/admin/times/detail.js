Vue.component('Detail', {
	template: `
		<el-dialog title="查看详情" width="650px" @open="open" class="icon-dialog" :visible.sync="show" :before-close="closeForm" append-to-body>
			<table cellpadding="0" cellspacing="0" class="table table-bordered" align="center" width="100%" style="word-break:break-all; margin-bottom:50px;  font-size:13px;">
				<tbody>
					<tr>
						<td class="title" width="100">普通：</td>
						<td>
							{{parseTime(form.a)}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">年月日：</td>
						<td>
							{{parseTime(form.b,'{y}-{m}-{d}')}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">年：</td>
						<td>
							{{form.year}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">月：</td>
						<td>
							{{form.month}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">时分秒：</td>
						<td>
							{{form.sfm}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">datetime格式：</td>
						<td>
							{{parseTime(form.d,'{y}-{m}-{d}')}}
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
			axios.post(base_url+'/Times/detail',this.info).then(res => {
				this.form = res.data.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
})
