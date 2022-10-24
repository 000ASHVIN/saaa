<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			3. Vitality Assessment
		</h4>
	</div>
	<div class="panel-body">
		<app-error-alert :form="forms.assessment"></app-error-alert>
            
            <div class="row">
                    <div class="col-md-9">         
                        <label for="registered-body">Are you a member of a registered professional accountancy body? &nbsp;</label> 
                    </div>

                  <div class="col-md-3 text-right">
                      <div class="form-group">
                          <label class="radio-inline">
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="1" v-model="forms.assessment.registered" number>
                            YES
                          </label>

                          <label class="radio-inline">
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="0" v-model="forms.assessment.registered" number>
                            NO
                          </label>
                        
                    </div>
                  </div>
              </div>                  

                    <div v-if="forms.assessment.registered">
                        <div class="spacer-10"></div>
                        <label>Select name of professional body</label>
                          <div class="row">
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="AAT" value="AAT" v-model="forms.assessment.body">
                                    AAT
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="CIMA" value="CIMA" v-model="forms.assessment.body">
                                    CIMA
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="IAC" value="IAC" v-model="forms.assessment.body">
                                    IAC
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="IIA" value="IIA" v-model="forms.assessment.body">
                                    IIA
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="SAICA" value="SAICA" v-model="forms.assessment.body">
                                    SAICA
                                  </label>
                                </div>
                            </div>                    
                          </div>
                      
                            {{-- <div class="spacer-10"></div> --}}

                          <div class="row">
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="ACCA" value="ACCA" v-model="forms.assessment.body">
                                    ACCA
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="CIS" value="CIS" v-model="forms.assessment.body">   
                                    CIS
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="ICBA" value="ICBA" v-model="forms.assessment.body">    
                                    ICBA
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="SAIBA" value="SAIBA" v-model="forms.assessment.body">   
                                    SAIBA
                                  </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="SAIPA" value="SAIPA" v-model="forms.assessment.body">
                                    SAIPA
                                  </label>
                                </div>
                            </div>  
                            <div class="col-sm-2">
                              <div class="form-group">
                                  <label class="radio-inline">
                                    <input type="radio" name="optionBodies" id="OTHER" value="OTHER" v-model="forms.assessment.body">              
                                    OTHER
                                  </label>
                                </div>
                            </div>                  
                          </div>  
                      </div>

                      <div class="spacer-10"></div>

                        <div class="row">
                          <div class="col-md-9">
                          <label>Do you adhere to a Code of Conduct that is equal or similar to the IFAC Code of Conduct?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosOne" id="optionsRadios1" value="1" v-model="forms.assessment.options.conduct" number>                                
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosOne" id="optionsRadios2" value="0" v-model="forms.assessment.options.conduct" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="spacer-5"></div>
                        
                        <div class="col-md-9">
                          <label>Are your CPD hours up to date as required by your professional body?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosTwo" id="optionsRadios1" value="1" v-model="forms.assessment.options.cpd" number>
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosTwo" id="optionsRadios2" value="0" v-model="forms.assessment.options.cpd" number>
                                NO
                              </label>
                            </div>
                          </div>
                        </div>        

                        <div class="spacer-5"></div>

                        <div class="col-md-9">
                          <label>Do you use engagement letters for all clients?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                          <div class="form-group">
                            <label class="radio-inline">
                              <input type="radio" name="optionsRadiosThree" id="optionsRadios1" value="1" v-model="forms.assessment.options.engagement" number>                              
                              YES
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="optionsRadiosThree" id="optionsRadios2" value="0" v-model="forms.assessment.options.engagement" number>                              
                              NO
                            </label>
                          </div>
                        </div>
                        </div>     

                        <div class="spacer-5"></div>   

                        <div class="col-md-9">
                          <label>Do you have access to the latest technical knowledge or library?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosFour" id="optionsRadios1" value="1" v-model="forms.assessment.options.technical" number>                                
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosFour" id="optionsRadios2" value="0" v-model="forms.assessment.options.technical" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>        
                        
                        <div class="spacer-5"></div>

                        <div class="col-md-9">
                          <label>Do you have the required infrastructure and resources to perform professional work for clients?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosFive" id="optionsRadios1" value="1" v-model="forms.assessment.options.resources" number>
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosFive" id="optionsRadios2" value="0" v-model="forms.assessment.options.resources" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>   

                        <div class="spacer-5"></div>  

                        <div class="col-md-9">
                          <label>Do you or your firm perform reviews of all work performed by your professional support staff?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosSix" id="optionsRadios1" value="1" v-model="forms.assessment.options.reviews" number>                                
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosSix" id="optionsRadios2" value="0" v-model="forms.assessment.options.reviews" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>   

                        <div class="spacer-5"></div>  

                        <div class="col-md-9">
                          <label>Do you apply relevant auditing and assurance standards when issuing reports on financial statements for clients?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosSeven" id="optionsRadios1" value="1" v-model="forms.assessment.options.standards" number>                                
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosSeven" id="optionsRadios2" value="0" v-model="forms.assessment.options.standards" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>   

                        <div class="spacer-5"></div>  

                        <div class="col-md-9">
                          <label>Do you use the latest technology and software to manage your practice and perform professional work?</label> 
                        </div>

                        <div class="col-md-3 text-right">
                          <div class="form-group">
                            <div class="form-group">
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosEight" id="optionsRadios1" value="1" v-model="forms.assessment.options.technology" number>                                
                                YES
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="optionsRadiosEight" id="optionsRadios2" value="0" v-model="forms.assessment.options.technology" number>                                
                                NO
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="spacer-10"></div>

                      <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary" @click.prevent="completeAssessment" :disabled="forms.assessment.busy">
                                    <span v-if="forms.assessment.busy">
                                        <i class="fa fa-btn fa-spinner fa-spin"></i> Saving...
                                    </span>

                                    <span v-else>
                                        <i class="fa fa-btn fa-check-circle"></i> Save & Continue
                                    </span>
                                </button>
                            </div>
                        </div>
                      
                </div>
              </div>            