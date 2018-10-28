<template>
    <k-field v-bind="$attrs">
    	<k-list-item v-if="count == 0"
    				 :text="$t('colorextractor.button.text.empty')" 
    				 :icon="iconEmpty" 
    				 element="div" 
    				 class="colorextractor-empty" />
    	<k-list-item v-else
    				 :text="$t('colorextractor.button.text')" 
    				 :icon="icon" 
    				 element="div" 
    				 class="colorextractor-button"
    				 @click="openDialog" />

    	<k-dialog ref="dialog" theme="negative">
		    <template v-if="!processing && !completedCount">
	    		<k-text v-html="$t('colorextractor.dialog.text', {count: count}, count)">
	    		</k-text>
				<template slot="footer">
				    <k-button-group>
				        <k-button icon="cancel" @click="$refs.dialog.close()">{{ $t('cancel') }}</k-button>
				        <k-button icon="check" theme="positive" @click="processImages">Process</k-button>
				    </k-button-group>
				</template>
		    </template>
		    <template v-else-if="!processing && failed.length">
	    		<k-text>
	    			{{ $t('colorextractor.dialog.text.error', {count: completedCount, failed: failed.length}, completedCount) }}
	    		</k-text>
	    		<ul class="colorextractor-errors">
	    			<li v-for="(obj, index) in failed" class="error">
	    				<strong>Filename:</strong> {{ obj.name }}
	    				<div class="error-message">{{obj.message}}</div>
	    			</li>
	    		</ul>
				<template slot="footer">
				    <k-button-group>
				        <k-button icon="cancel" @click="$refs.dialog.close()">{{ $t('close') }}</k-button>
				    </k-button-group>
				</template>
		    </template>
		    <template v-else-if="processing">
			    <k-headline>{{ $t('colorextractor.dialog.processing') }}</k-headline>
		        <k-progress ref="progress"/>
			    <ul class="k-upload-list">
			        <div class="colorextractor-progress-caption">
		            	<p class="k-colextractor-counter">{{ $t('colorextractor.dialog.extracted') }}: {{completedCount}} / {{count}}</p>
		            </div>
		        </ul>
				<template slot="footer">
				    <k-button-group>
				      <k-button icon="cancel" @click="abortExtraction">{{ $t('cancel') }}</k-button>
				    </k-button-group>
				</template>
		    </template>
		</k-dialog>
    </k-field>
</template>

<script>

export default {
	data() { 
		return {
			iconEmpty: {
				type: 'check',
				back: 'theme-empty'
			},
			icon: {
				type: 'refresh',
				back: 'theme-process'
			},
			processing: false,
			completed: [],
			failed: [],
		}
	},
	props: {
		files: Array,
	},
	computed: {
		count: function() {
			let count = this.files.length
			count = Object.is(count, undefined) ? 0 : count
			return count
		},
		completedCount: function() {
			let count = this.completed.length
			count = Object.is(count, undefined) ? 0 : count
			return count
		},
	},
	methods: {
	    processImages() {
	    	this.resetArrays()
	    	this.processing = true

	    	this.files.forEach((file, index) => {
	    		this.$api.post('colorextractor/process-image', {id: file.id, index: index})
	    			.then(response => {
	    				// the image has been processed
	    				this.completed.push(file.name)

				    	// if it was the last image to process
				    	if (this.completedCount == this.count) {
					        if (this.failed.length) this.failedExtraction()
					        else {
					        	let self = this
					        	setTimeout(() => {
					        		self.completedExtraction()
					        	}, 250)
					        }
					    }

					    this.setProgress()
	    			})
	    			// if there's been an error
	    			.catch(error => {
	    				// the image has also be processed
					    this.completed.push(file.name)
					    // but we add it to the failed array
					    this.failed.push({
			    			name: file.filename,
			    			message: error.message
			    		})
			    		// it it was the last iage to process
			    		if (this.completedCount == this.count) this.failedExtraction()

			    		this.setProgress()
					})
		    })
	    },
	    setProgress() {
			let percent = this.completedCount / this.count * 100;
			    percent = Math.max(0, Math.min(100, percent));
			this.$refs.progress.set(percent);
	    },
		completedExtraction() {
			let message = this.$t('colorextractor.notification.completed', {count: this.completedCount}, this.completedCount);

			this.$refs.dialog.close();
			this.$store.dispatch("notification/success", message);

	    	this.processing = false
	    	this.currentIndex = 0
	    	this.files = {}
		},
		failedExtraction() {
			this.processing = false
	    	this.currentIndex = 0
	    	this.files = this.files.filter(file => {
				return this.failed.filter(e => e.name === file.filename).length;
			})
		},
		openDialog() {
			this.resetArrays()
			this.$refs.dialog.open()
		},
	    abortExtraction() {
	    	this.$refs.dialog.close();
	    	this.processing = false;
	    },
	    resetArrays() {
	    	this.completed = [];
	    	this.failed = [];
	    }
	},
}
</script>

<style lang="scss">
	.k-icon[data-back="theme-empty"],
	.k-icon[data-back="theme-process"] { 
        background: #f5f5f5;
	}
	.k-icon[data-back="theme-empty"] svg { 
		color: #a7bd69; 
	}
	.k-icon[data-back="theme-process"] svg { 
		color: #16171a; 
	}
	.colorextractor-empty {
		user-select: none;
	}
	.colorextractor-button {
		user-select: none;
		cursor: pointer;
	}
	.colorextractor-progress-caption {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-top: 3px;
	}
	.colorextractor-errors {
		font-size: 0.75rem;
		margin-top: 1rem;
	}
	.colorextractor-errors .error-message {
		background: lighten(#d16464, 25%);
		border-left: 2px solid #d16464;
		padding: 0.5rem;
		margin-top: 3px;
	}
	.colorextractor-errors .error + .error {
		margin-top: 0.75rem;
	}
	.k-colextractor-counter {
		color: #777;
		line-height: 1.5em;
		font-size: .875rem;
		white-space: pre;
	}
</style>