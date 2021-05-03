<template>

	<div class="form-group edit-fields autocomplete-div">
        <label for="input_field"> {{rusname}} </label>
		<input type="text" id="input_field "name="input_field"
					placeholder="Введите Подразделение" class="form-control"
					v-model="query" 
					@input="autoComplete">
		<input type="hidden" :id="name" :name="name">

		   <ul class="list-group autocomplete-items" v-show="isOpen" >
			    <li class="list-group-item" v-for="result in results"
			    	:key="result.id"
			    	@click="setResult(result)"
			    	style="cursor:pointer;">
			     {{ result.orgUnitCode }}
			    </li>
		   </ul>
	</div>

</template>

<script>
	export default {

		props: ['name', 'rusname','path'],

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
				this.query = result.orgUnitCode;
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