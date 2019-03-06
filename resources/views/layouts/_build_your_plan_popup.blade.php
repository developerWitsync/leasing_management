
<!-- modal for BUILD plan -->
<div id="buildYourPlan_Modal" class="modal fade buildYourPlan_Modal" role="dialog" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 left">
                        <h2>Request a Personalized Quote</h2>
                    </div>
                    <div class="col-md-7 right">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h1 class="text-center">Build Your Customized Annual Subscription Plan</h1>
                        <h4>Tell us your needs and we will build the perfact plan for you.</h4>
                        <div class="alert alert-success buildyourplan_success" style="display: none">

                        </div>

                        <form action="{{ route('master.pricing.buildyourplan') }}" id="buildYourPlan_Form">
                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>

                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="phone">Mobile No.</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="no_of_lease_assets">Number of Lease Assets</label>
                                <input type="number" class="form-control" id="no_of_lease_assets" name="no_of_lease_assets">
                            </div>
                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="no_of_users">Number of Users</label>
                                <input type="number" class="form-control" name="no_of_users" id="no_of_users">
                            </div>
                            <div class="form-group col-md-6 required">
                                <label class="control-label" for="hosting_type">Hosting Plan</label>
                                <select class="form-control" name="hosting_type" id="hosting_type">
                                    <option value="" disabled selected>Please Select</option>
                                    <option value="cloud">Cloud Hosting</option>
                                    <option value="on-premise">On-Premise Hosting</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="comments">Comments</label>
                                <textarea name="comments" id="comments" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-default submit-btn">SEND REQUEST</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>