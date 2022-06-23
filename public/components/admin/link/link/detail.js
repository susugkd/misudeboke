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
						<td class="title" width="100">分类名称：</td>
						<td>
							{{form.linkcata.class_name}}
						</td>
					</tr>
					<tr>
						<td class="title" width="100">具体代码：</td>
						<td>
							{{form.url}}
						</td>
					</tr>
					
					<tr>
						<td class="title" width="100">图解：</td>
						<td>
							<el-image v-if="form.logo" class="table_list_pic" :src="form.logo"  :preview-src-list="[form.logo]"></el-image>
						</td>
					</tr>
					
					<tr>
						<td class="title" width="100">创建时间：</td>
						<td>
							{{parseTime(form.create_time,'{y}-{m}-{d}')}}
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
				linkcata:{},
			},
		}
	},
	methods: {
		open(){
			axios.post(base_url+'/Link.Link/detail',this.info).then(res => {
				this.form = res.data.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
})
