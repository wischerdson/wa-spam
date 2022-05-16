<template>
	<div class="pt-16">
		<div class="container">
			<nuxt-link class="text-blue-500 underline" to="/">Назад</nuxt-link>
			<form class="mt-6" @submit.prevent="addAccount">
				<fieldset class="block">
					<label class="block text-sm" for="input_instance_id">Введите instanceID</label>
					<input class="bg-gray-200 mt-1 p-2" id="input_instance_id" type="text" v-model="instanceId">
				</fieldset>
				<button class="bg-blue-600 text-white p-4 mt-4" type="submit">Добавить</button>
			</form>
		</div>
	</div>
</template>

<script>

import { addAccount } from '~/services/api'

export default {
	data () {
		return {
			instanceId: ''
		}
	},
	methods: {
		addAccount () {
			addAccount({ instance_id: this.instanceId })
				.then(() => alert('Ok'))
				.catch(({ response }) => alert(response.data.message))
		}
	}
}

</script>
