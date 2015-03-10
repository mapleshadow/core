
/**
 * Copyright (c) 2015, Arthur Schiwon <blizzz@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

OCA = OCA || {};

(function() {

	var WizardDetectorBaseDN = OCA.LDAP.Wizard.WizardDetectorGeneric.subClass({
		init: function() {
			this.setTrigger([
				'ldap_host',
				'ldap_port',
				'ldap_dn',
				'ldap_agent_password'
			]);
			this.setTargetKey('ldap_dn');
		},

		run: function(model, configID) {
			if(    !model.configuration['ldap_host']
				|| !model.configuration['ldap_port']
				|| !model.configuration['ldap_dn']
				|| !model.configuration['ldap_agent_password']
				)
			{
				return false;
			}
			model.notifyAboutDetectionStart('ldap_dn');
			var params = OC.buildQueryString({
				action: 'guessBaseDN',
				ldap_serverconfig_chooser: configID
			});
			return model.callWizard(params, this.processResult, this);
		}
	});

	OCA.LDAP.Wizard.WizardDetectorBaseDN = WizardDetectorBaseDN;
})();
