<template>
	<div class="bg-amber-300">
		<nuxt />
	</div>
</template>

<script>

export default {
	head () {
		return {
			bodyAttrs: { style: 'background-color: #f7f8fb' }
		}
	}
}

</script>
