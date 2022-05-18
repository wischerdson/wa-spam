<template>
	<div class="mt-16">
		<div class="container">
			<div>
				<button class="bg-red-600 text-white p-4" @click="stopBot">Стоой бля</button>
			</div>
			<form class="mt-8" @submit.prevent="updateMessage">
				<fieldset>
					<label class="block text-sm" for="textarea_message">Ответное сообщение</label>
					<textarea class="bg-gray-200 p-2 mt-1 w-full min-h-[100px]" v-model="message"></textarea>
				</fieldset>
				<div class="flex space-x-6 mt-4 items-center">
					<button class="bg-blue-600 text-white p-4" type="submit">Обновить сообщение</button>
					<input type="file" @change="handleFileUploading">
				</div>
			</form>
			<div class="mt-12" v-if="!accounts.length">
				Аккаунты еще не добавлены
			</div>
			<ul class="grid grid-cols-4 gap-8 mt-12">
				<li class="bg-white shadow-lg relative after:block after:pt-2/3 rounded-xl">
					<v-action to="/add-account" class="absolute inset-0 px-6 py-6 mx-auto">
						<div class="text-gray-500 text-3xl">+ Добавить</div>
					</v-action>
				</li>
				<li class="bg-white shadow-lg relative after:block after:pt-2/3 rounded-xl" v-for="(account, idx) in accounts" :key="`account-${idx}`"  v-if="accounts.length">
					<div class="absolute inset-0 px-6 py-6">
						<div>
							<span>InstanceID: </span>
							<span>{{ account.whatsmonster_id }}</span>
						</div>
						<!-- <div class="absolute bottom-0 right-0 pb-4 pr-4 flex space-x-2">
							<v-action class="bg-red-500 rounded-xl px-4 text-white font-light" to="/">Удалить</v-action>
							<v-action class="!px-4 !py-2 !font-light" to="/" button>Выбрать</v-action>
						</div> -->
					</div>
				</li>
			</ul>

		</div>

	</div>
</template>

<script>

import { getAccounts, getMessage, updateMessage, uploadFile } from '~/services/api'

export default {
	layout: 'default',
	data () {
		return {
			accounts: [],
			message: '',
			file: ''
		}
	},
	fetchOnServer: true,
	async fetch () {
		this.accounts = (await getAccounts()).data
		this.message = (await getMessage()).data
	},
	methods: {
		updateMessage () {
			updateMessage({ message: this.message })
				.then(() => alert('Ok'))
				.catch(({ response }) => alert(response.data.message))
		},
		handleFileUploading ({ target }) {
			const file = target.files[0]
			const formData = new FormData()
			formData.append('file', file)
			uploadFile(formData)
		}
	}
}

</script>
