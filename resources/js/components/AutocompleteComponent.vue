<template>

	<div class="form-group edit-fields autocomplete-div">
        <label :for="id"> {{rusname}} </label>
		<div class="input-group">
			<input :type="type" :id="id" :name="id"
					:required="required"
					:placeholder="rusname" class="form-control"
					v-model="query" v-if="step" :step="step"
					@input="autoComplete">

			<input :type="type" :id="id" :name="id"
					:required="required"
					:placeholder="rusname" class="form-control"
					v-model="query" v-else :step="step"
					@input="autoComplete">
			<span class="input-group-text">{{groupText}}</span>
		</div>

		<input type="hidden" :id="name" :name="name">

		   <ul class="list-group autocomplete-items" v-show="isOpen" style="z-index:10000;">
			    <li class="list-group-item" v-for="result in results"
			    	:key="result.id"
			    	@click="setResult(result)"
			    	style="cursor:pointer;">
			     {{ result[name] }}
			    </li>
		   </ul>
	</div>

</template>

<script>
	export default {

		props: ['name', 
				'rusname',
				'path', 
				'id', 
				'type', 
				'groupText', 
				'step',
				'required',
				'givenValue'],

		//после создания	
	  	mounted() {
    		document.addEventListener('click', this.handleClickOutside);
 	 	},

 	 	//после удаления
 	 	destroyed() {
    		document.removeEventListener('click', this.handleClickOutside);
  		},

  		data(){
			return {
				query: '',
				isOpen: false,
				results: []
			}
		},

		methods: {
			setResult(result){
				this.isOpen = false;
				this.query = result[this.name];
			},

			autoComplete(){
				this.results = [];
				axios.get(this.path,{params: {query: this.query}})
					.then(response => {
						this.results = response.data;
						this.isOpen = true;
				});
			},

			handleClickOutside(e){
				if(!this.$el.contains(e.target))
					this.isOpen = false;
			}
		}

	}
</script>