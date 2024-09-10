<template>
  <div>
    <label for="roles">Assign Roles</label>
    <multiselect
      v-model="selectedRoles"
      :options="roles"
      :multiple="true"
      :taggable="false"
      :track-by="'id'"
      :label="'name'"
      placeholder="Select roles"
      @input="handleRoleChange"
      :class="{ 'superuser-disabled': isSuperuserSelected }"
    >
      <template #option="{ option }">
        <span :class="{ 'disabled-option': isSuperuserSelected && option.id != 1 }">
          {{ option.name }}
        </span>
      </template>
    </multiselect>

    <div class="form-group mt-3">
      <input type="checkbox" id="is_superuser" v-model="isSuperuserSelected" />
      <label for="is_superuser">Superadmin</label>
      <p v-if="isSuperuserSelected" class="superuser-text">This user is a Superuser.</p>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

export default {
  components: {
    Multiselect,
  },
  props: {
    roles: Array,
    initialRoles: Array
  },
  data() {
    return {
      selectedRoles: this.initialRoles,
      isSuperuserSelected: this.initialRoles.includes('1')
    };
  },
  methods: {
    handleRoleChange() {
      if (this.isSuperuserSelected) {
        this.selectedRoles = this.selectedRoles.filter(role => role === '1' || role);
      }
    }
  }
};
</script>

<style scoped>
.disabled-option {
  color: #ccc;
}
.superuser-disabled .multiselect__option {
  color: #ccc;
}
.superuser-text {
  color: red;
  font-weight: bold;
}
</style>
