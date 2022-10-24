<template>
    <section>
        <h3>Algemeen</h3>
        <form class="row g-3" @submit.prevent="saveSettings()" id="settings-form">
            <div class="col-md-6">
                <label for="inputSchoolName" class="form-label">School naam</label>
                <input type="text" class="form-control" :class="{'is-invalid': settingsForm.errors.school_name}"
                       id="inputSchoolName" v-model="settingsForm.school_name" aria-describedby="feedbackSchoolName"
                       required min="2" max="255">
                <div id="feedbackSchoolName" class="invalid-feedback" v-if="settingsForm.errors.school_name">
                    {{ settingsForm.errors.school_name }}
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
            </div>
        </form>
    </section>
    <section id="standard-lessons-form" class="mt-4">
        <h3>Standaard lessen</h3>
        <div v-for="(lesson, index) in lessons" class="d-flex align-items-center mb-2">
            <h6 class="m-0">{{ lesson.id }}. &nbsp;</h6>
            <input type="time" class="form-control" v-model="lesson.from"/>
            <span class="mx-2">t/m</span>
            <input type="time" class="form-control" v-model="lesson.to">
        </div>
        <button class="btn btn-sm btn-outline-success" @click="addNewLesson">
            <i class="fa-solid fa-plus"></i> Nieuwe toevoegen
        </button>
        <br/><br/>
        <button class="btn btn-success" @click="saveLessons"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
    </section>
    <section class="mt-4">
        <h3>Studenten</h3>
        <form class="row g-3" @submit.prevent="saveStudentsAutoIncrement">
            <div class="col-md-6">
                <Input label="Leerlingnummer teller" v-model="studentsIncrement" :error="$page.props.errors.students_increment"/>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
            </div>
        </form>
    </section>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import Input from "@/Components/Inputs/InputWithLabel.vue";

export default {
    components: {Input},
    layout: AdminLayout,
    props: {
        settings: Object,
        standardLessons: Object,
        maxStudentsAutoIncrement: Number
    },
    data() {
        return {
            settingsForm: useForm({
                school_name: this.settings.school_name
            }),
            lessons: this.standardLessons,
            studentsIncrement: this.maxStudentsAutoIncrement
        }
    },
    methods: {
        saveSettings() {
            this.settingsForm.patch(route('admin.settings.update'), {
                preserveScroll: true,
                onSuccess: () => {
                    // this.settingsForm.reset();
                    this.toast('success', 'Succesvol opgeslagen!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            })
        },
        addNewLesson() {
            this.lessons.push({
                id: this.lessons.length + 1,
                from: null,
                to: null
            })
        },
        saveLessons() {
            Inertia.patch(route('admin.settings.lessons.update'), {
                standardLessons: this.lessons
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.toast('success', 'Succesvol uren aangepast!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            });
        },
        saveStudentsAutoIncrement() {
            Inertia.patch(route('admin.settings.students-auto-increment'), {
                students_increment: this.studentsIncrement
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.toast('success', 'Succesvol studenten leerlingnummer teller aangepast!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            });
        }
    }
}
</script>
<style lang="scss">
#standard-lessons-form {
    input[type=time] {
        max-width: 200px;
    }
}
</style>
