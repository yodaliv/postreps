import helper from './helper';

let region = {

    init(){
        this.initDatatable();
        this.delete();
    },

    initDatatable(){
        $('#regionsTable').DataTable({
            responsive: true,
            order:[[0,'asc']],
            language: { search: '', searchPlaceholder: "Search..." },
        });
    },

    delete(){
        $('body').on('click', '.deleteRegionBtn', e => {
            const self = $(e.target);
            const regionId = self.data('id');
            const form = $(`#deleteRegionForm${regionId}`);

            helper.confirm('',
                () => {
                    form.trigger('submit');
                },
                () => {

                }
            );
        });
    },
}

$(()=>{
    region.init();
});
