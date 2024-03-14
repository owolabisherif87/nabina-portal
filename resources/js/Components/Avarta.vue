<script setup>

</script>

<template>
    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200">
        <img :src="$page.props.auth.user?.image" alt="avarta" class="w-full h-full object-cover">
    </div>
</template>
