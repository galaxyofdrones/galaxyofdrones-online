<navigation v-if="isRouteName('home')" inline-template>
    <div class="navigation" v-cloak>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link"
                   href="#"
                   @click.prevent="prev()">
                    <i class="fas fa-arrow-up"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   href="#"
                   @click.prevent="next()">
                    <i class="fas fa-arrow-down"></i>
                </a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link"
                   href="#"
                   @click.prevent="upgradeAll()">
                    <i class="fas fa-upload"></i>
                </a>
            </li>
        </ul>
    </div>
</navigation>
