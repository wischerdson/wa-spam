import Vue from 'vue'

const components = {
	Icon: require('~/ui/Icon').default,
	Action: require('~/ui/Action').default,
	Input: require('~/ui/Input').default
}

Object.entries(components).forEach(([name, component]) => {
	Vue.component('V' + name, component)
})
