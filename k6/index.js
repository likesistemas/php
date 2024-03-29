import http from 'k6/http';
import { check } from 'k6';
import { Counter } from 'k6/metrics';

export const requests = new Counter('http_reqs');

export const urlbase = __ENV.URL != null ? __ENV.URL : "http://127.0.0.1/";

export const options = {
  thresholds: {
    http_req_duration: ['p(95)<500'],
  },
};

export default function () {
  const ok = {
    'status is 200': (r) => r.status === 200,
    'response body': (r) => {
      if(r.body === undefined || r.body === null) {
        return false;
      }

      return r.body.indexOf('Like Sistemas|123') !== -1;
    },
  };

  check(http.get(urlbase + ''), ok);
}