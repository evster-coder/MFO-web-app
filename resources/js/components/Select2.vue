<template>
	<select>
		<slot></slot>
	</select>
</template>

<script>
	import Select2 from "select2";

	export default{
		props: ['options', 'value', 'orgUnitCode'],

		mounted(){
			var vm = this;

			$(this.$el).select2({
				theme: "bootstrap-5",
			    placeholder: "Выберите подразделение",
			    "language": {
			        "noResults": function(){
			            return "Ничего не найдено";
			        }
			    },
				data: this.options,
			})
			.val(this.value)
			.trigger('change')
			.on('change', function(){
				vm.$emit('input', this.value);
			});
		},

		watch: {
			value: function(param){
				$(this.$el).val(param).trigger('change');
			},

			options: function(param){
				$(this.$el).select2({
					data: param
				});
			},

			destroyed: function(){
				($this.$el).off().select2('destroy');
			}
		}
	}
</script>