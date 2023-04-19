# -*- test-case-name: pulse2.cm.tests.parse -*-
# -*- coding: utf-8; -*-
# SPDX-FileCopyrightText: 2014 Mandriva, http://www.mandriva.com/
# SPDX-FileCopyrightText: 2016-2023 Siveo <support@siveo.net> 
# SPDX-License-Identifier: GPL-2.0-or-later

class Parser(object):
    """ A simple wrapper for several serializers """

    def __init__(self, backend="json"):
        self._set_backend(backend)


    def _set_backend(self, backend):
        if backend == "json":
            import json
            self._backend = json

        elif backend == "marshal":
            import marshal
            self._backend = marshal

        elif backend == "pickle":
            try:
                import cPickle
                self._backend = cPickle
            except ImportError:
                import pickle
                self._backend = pickle
        else:
            raise TypeError, "Unknown parser type: %s" % backend

    def encode(self, value):
        return self._backend.dumps(value)

    def decode(self, value):
        return self._backend.loads(value)