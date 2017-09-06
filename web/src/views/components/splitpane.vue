<template>
	<div class="components-container">
		<split-pane v-on:resize="resize" split="vertical">
			<template slot="paneL">
				<div class="left-container"></div>
			</template>
			<template slot="paneR">
				<split-pane split="horizontal">
					<template slot="paneL">
						<div class="top-container"></div>
					</template>
					<template slot="paneR">
						<div class="bottom-container">
						</div>
					</template>
				</split-pane>
			</template>
		</split-pane>
	</div>
</template>

<script>
import splitPane from 'vue-splitpane'

export default {
  components: { splitPane },
  methods: {
    resize() {
      console.log('resize')
    }
  }
}
</script>

<style  scoped>
	.components-container {
		position: relative;
		height: 100vh;
	}

	.left-container {
		background-color: #F38181;
		height: 100%;
	}

	.right-container {
		background-color: #FCE38A;
		height: 200px;
	}

	.top-container {
		background-color: #FCE38A;
		width: 100%;
		height: 100%;
	}

	.bottom-container {
		width: 100%;
		background-color: #95E1D3;
		height: 100%;
	}
</style>
